#include <WiFi.h>
#include <HTTPClient.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Adafruit_Fingerprint.h>
#include <ArduinoJson.h>
#include <time.h> 
#include <HardwareSerial.h>
#include <WiFiManager.h> 
#include <RTClib.h> 
#include <Preferences.h> 

// ================= FIREBASE CONFIGURATION =================
#include <Firebase_ESP_Client.h>
#include "addons/TokenHelper.h"
#include "addons/RTDBHelper.h"

FirebaseData fbdo;
FirebaseData fbdoStream; // Object khusus untuk mendengarkan stream real-time
FirebaseAuth auth;
FirebaseConfig configFirebase;

// ================= STORAGE MANAGEMENT (PREFERENCES) =================
Preferences preferences;
String baseUrl;
String deviceToken;
String firebaseUrl;     
String firebaseSecret;  

// ================= KONFIGURASI NTP (WAKTU INTERNET) =================
const char* ntpServer = "pool.ntp.org";
const long   gmtOffset_sec = 25200; // GMT+7 (WIB)
const int    daylightOffset_sec = 0;

// ================= KONFIGURASI HARDWARE =================
#define BUZZER_PIN 4
HardwareSerial mySerial(2); // RX = 16, TX = 17 di ESP32

LiquidCrystal_I2C lcd(0x27, 16, 2); 
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);
RTC_DS3231 rtc; 

// ================= TIMING MANAGEMENT =================
unsigned long lastPollTime = 0;
const unsigned long pollInterval = 60000; // Polling Laravel diubah ke 60 detik (1 menit) hanya untuk update status Online, agar server Laravel tidak berat.

unsigned long lastSerialTime = 0;        
const unsigned long serialInterval = 5000;

unsigned long lastFingerCheckTime = 0;
const unsigned long fingerCheckInterval = 150; 

unsigned long lastFirebasePollTime = 0;
const unsigned long firebasePollInterval = 3000; // Polling Firebase tiap 3 detik

bool fingerAvailable = false; 
bool rtcAvailable = false;
bool firebaseConnected = false;

// --- STATE MACHINE LCD ---
int lcdState = 0; 
unsigned long lcdTimer = 0;

// Objek WiFiManager & Parameter Kustom
WiFiManager wm;
WiFiManagerParameter* custom_baseUrl;
WiFiManagerParameter* custom_deviceToken;
WiFiManagerParameter* custom_firebase_url;
WiFiManagerParameter* custom_firebase_secret;

// Deklarasi Prototip Fungsi
void cetakLCD(int baris, String teks);
void configModeCallback(WiFiManager *myWiFiManager);
void saveParamCallback();
void syncWaktuNTP();
void tampilkanJam();
int getFingerprintID();
void kirimPresensi(int id, String timeStr);
void cekStatusServer();
void cekDataFirebase(); 
void prosesEnroll(int id);
void konfirmasiEnrollServer(int id, String polaHex, String status);
void prosesSync(int id, String polaHex);
void prosesHapus(int id, int commandId);
void konfirmasiHapusServer(int id, int commandId);
void bunyiBuzzer(int jumlah, int durasi);
void loadSavedConfig();
String getFingerprintTemplateHex();

// ================= FUNGSI HELPER LCD 16x2 =================
void cetakLCD(int baris, String teks) {
  lcd.setCursor(0, baris);
  while (teks.length() < 16) {
    teks += " "; 
  }
  lcd.print(teks.substring(0, 16)); 
}

// ================= SETUP UTAMA =================
void setup() {
  Serial.begin(115200);
  Serial.println("\n\n=======================================================");
  Serial.println("[SYSTEM] BOOTING ABSENSI (FULL DUAL SYSTEM LARAVEL & FIREBASE)");
  Serial.println("=======================================================");
  
  digitalWrite(BUZZER_PIN, HIGH); 
  pinMode(BUZZER_PIN, OUTPUT);
  
  Wire.begin(21, 22); 
  delay(100);

  Serial.println("[LCD] Menginisialisasi LCD I2C...");
  lcd.init();
  lcd.backlight();
  cetakLCD(0, " ABSENSI PINTAR ");
  cetakLCD(1, "  SMART DEVICE  ");
  delay(2000);

  // Load konfigurasi dari memori ESP32
  loadSavedConfig();

  // Init RTC
  Serial.println("\n[RTC] --- MENDETEKSI MODUL DS3231 ---");
  if (!rtc.begin()) {
    Serial.println("[RTC] ERROR: Modul RTC tidak terdeteksi!");
    cetakLCD(0, " SYSTEM WARNING ");
    cetakLCD(1, "RTC ERROR / KBL ");
    delay(2000);
    rtcAvailable = false;
  } else {
    Serial.println("[RTC] SUKSES: Modul RTC aktif dan terhubung.");
    rtcAvailable = true;
    if (rtc.lostPower()) {
      Serial.println("[RTC] WARN: Baterai habis. Menyetel waktu ke compile-time...");
      rtc.adjust(DateTime(F(__DATE__), F(__TIME__))); 
    }
  }

  // Init Fingerprint
  Serial.println("\n[FINGER] --- MENGINISIALISASI SENSOR AS608 ---");
  mySerial.begin(57600, SERIAL_8N1, 17, 16);
  delay(100); 
  
  finger.begin(57600);
  if (finger.verifyPassword()) {
    Serial.println("[FINGER] SUKSES: Sensor AS608 merespons dengan baik.");
    fingerAvailable = true;
  } else {
    Serial.println("[FINGER] ERROR: Sensor AS608 tidak merespons!");
    cetakLCD(0, " SYSTEM WARNING ");
    cetakLCD(1, "SENSOR FINGER ER");
    delay(2000);
    fingerAvailable = false; 
  }

  // Init WiFiManager
  Serial.println("\n[WIFI] --- MEMULAI WIFIMANAGER ---");
  cetakLCD(0, "CONNECTING WIFI ");
  cetakLCD(1, "MENCARI JARINGAN");

  wm.setAPCallback(configModeCallback);
  
  // Parameter WiFiManager dari data yang tersimpan
  custom_baseUrl = new WiFiManagerParameter("server_url", "Base URL API Laravel", baseUrl.c_str(), 100);
  custom_deviceToken = new WiFiManagerParameter("dev_token", "Device Token Perangkat", deviceToken.c_str(), 50);
  custom_firebase_url = new WiFiManagerParameter("fb_url", "Firebase DB URL (Tanpa https://)", firebaseUrl.c_str(), 150);
  custom_firebase_secret = new WiFiManagerParameter("fb_secret", "Firebase Secret Key", firebaseSecret.c_str(), 100);

  wm.addParameter(custom_baseUrl);
  wm.addParameter(custom_deviceToken);
  wm.addParameter(custom_firebase_url);
  wm.addParameter(custom_firebase_secret);
  
  wm.setParamsPage(true); 
  wm.setSaveParamsCallback(saveParamCallback); 

  // Coba connect, jika gagal buat AP "Mesin_Absensi" password "12345678"
  if (!wm.autoConnect("Mesin_Absensi", "12345678")) {
    Serial.println("[WIFI] Gagal terhubung. Menjalankan Mode Offline (Timeout).");
    cetakLCD(0, "  MODE OFFLINE  ");
    cetakLCD(1, "TDK ADA INTERNET");
    delay(2000);
  } else {
    Serial.println("[WIFI] SUKSES: Terhubung ke Jaringan WiFi.");
    
    Serial.println("\n[SYSTEM] --- STATUS JARINGAN & PARAMETER ---");
    Serial.print(" => IP Address   : "); Serial.println(WiFi.localIP());
    
    cetakLCD(0, "WIFI CONNECTED! ");
    cetakLCD(1, WiFi.localIP().toString());
    delay(3000);
    
    wm.startWebPortal(); 
    syncWaktuNTP();

    // Init Firebase setelah WiFi terkoneksi
    Serial.println("\n[FIREBASE] --- MENGHUBUNGKAN KE FIREBASE ---");
    configFirebase.database_url = firebaseUrl.c_str();
    configFirebase.signer.tokens.legacy_token = firebaseSecret.c_str();
    
    Firebase.reconnectWiFi(true);
    Firebase.begin(&configFirebase, &auth);

    if (Firebase.ready()) {
      Serial.println("[FIREBASE] SUKSES Terhubung ke RTDB.");
      firebaseConnected = true;

      // Mulai mendengarkan data secara REAL-TIME dari path /commands/deviceToken
      if (!Firebase.RTDB.beginStream(&fbdoStream, "/commands/" + deviceToken)) {
        Serial.println("[FIREBASE] Gagal memulai stream: " + fbdoStream.errorReason());
      } else {
        Serial.println("[FIREBASE] Stream Real-Time AKTIF!");
      }
    } else {
      Serial.println("[FIREBASE] GAGAL Terhubung.");
    }
  }
  
  lcd.clear();
  Serial.println("\n[SYSTEM] SETUP SELESAI. SISTEM MASUK KE MODE STANDBY.\n");
}

// ================= LOAD / SAVE KONFIGURASI DARI MEMORI =================
void loadSavedConfig() {
  Serial.println("\n[MEMORI] --- MEMBACA KONFIGURASI TERSIMPAN ---");
  preferences.begin("config", true); 
  
  baseUrl = preferences.getString("baseUrl", "http://192.168.0.101:8000/api/fingerprint");
  deviceToken = preferences.getString("deviceToken", "0sL0YVgA6NOupcn5ASiRZ6DwyVgBA0Zo");
  firebaseUrl = preferences.getString("firebaseUrl", "presensimts-80d6a-default-rtdb.asia-southeast1.firebasedatabase.app");
  firebaseSecret = preferences.getString("firebaseSecret", "TULIS_SECRET_DISINI");
  
  preferences.end();
}

void saveParamCallback() {
  Serial.println("\n[CONFIG] Konfigurasi diubah melalui halaman Setup!");

  String newUrl = String(custom_baseUrl->getValue());
  String newToken = String(custom_deviceToken->getValue());
  String newFbUrl = String(custom_firebase_url->getValue());
  String newFbSecret = String(custom_firebase_secret->getValue());

  preferences.begin("config", false);
  baseUrl = newUrl;
  deviceToken = newToken;
  firebaseUrl = newFbUrl;
  firebaseSecret = newFbSecret;
  
  preferences.putString("baseUrl", baseUrl);
  preferences.putString("deviceToken", deviceToken);
  preferences.putString("firebaseUrl", firebaseUrl);
  preferences.putString("firebaseSecret", firebaseSecret);
  preferences.end();

  Serial.println(" => Config Tersimpan!");
  bunyiBuzzer(2, 100);
}

// ================= MAIN LOOP =================
void loop() {
  wm.process(); // Melayani akses portal WiFiManager di background

  char timeBuffer[25] = "0000-00-00 00:00:00";
  if (rtcAvailable) {
    DateTime now = rtc.now();
    sprintf(timeBuffer, "%04d-%02d-%02d %02d:%02d:%02d", now.year(), now.month(), now.day(), now.hour(), now.minute(), now.second());
  } else {
    sprintf(timeBuffer, "RTC-ERROR-NO-TIME"); 
  }

  // --- UI Control LCD ---
  if (lcdState == 0) {
    tampilkanJam(); 
  } 
  else if (lcdState == 1) {
    if (millis() - lcdTimer > 400) {
      cetakLCD(0, "SIDIK JARI SALAH");
      cetakLCD(1, "TIDAK DIKENALI! ");
      bunyiBuzzer(2, 120); 
      lcdState = 2; 
      lcdTimer = millis();
    }
  }
  else if (lcdState == 2 || lcdState == 3) {
    if (millis() - lcdTimer > 2500) { 
      lcdState = 0; 
    }
  }

  // Log detak sistem ke Serial Monitor
  if (millis() - lastSerialTime > serialInterval) {
    Serial.printf("[STANDBY] Waktu Saat Ini: %s | IP: %s\n", timeBuffer, WiFi.localIP().toString().c_str());
    lastSerialTime = millis();
  }

  // --- 1. CEK SENSOR SIDIK JARI (Mode Scan / Presensi) ---
  if (fingerAvailable && lcdState == 0 && (millis() - lastFingerCheckTime > fingerCheckInterval)) {
    lastFingerCheckTime = millis(); 
    int fingerStatus = getFingerprintID();
    
    if (fingerStatus > 0) { 
      kirimPresensi(fingerStatus, String(timeBuffer)); // Kirim ke Laravel lalu Firebase
    } 
    else if (fingerStatus == -1) { 
      lcdState = 1; lcdTimer = millis(); // Finger gagal dikenali
    }
  }

  // --- 2. POLLING COMMAND DARI SERVER & FIREBASE ---
  if (WiFi.status() == WL_CONNECTED) {
    // Polling API Laravel
    if (millis() - lastPollTime > pollInterval) {
      lastPollTime = millis();
      cekStatusServer(); 
    }

    // Listen Stream Real-time dari Firebase (Tanpa Polling!)
    if (Firebase.ready() && firebaseConnected) {
      if (Firebase.RTDB.readStream(&fbdoStream)) {
        if (fbdoStream.streamTimeout()) {
          Serial.println("[FIREBASE] Stream timeout, resume...");
        }
        if (fbdoStream.streamAvailable()) {
          if (fbdoStream.dataType() != "null") { 
            // Ada data perintah baru masuk!
            Serial.println("[FIREBASE] Perintah real-time diterima!");
            cekDataFirebase(); // Proses perintah tersebut
          }
        }
      }
    }
  }
  
  yield(); 
}

// ================= FUNGSI-FUNGSI PENDUKUNG =================

void configModeCallback(WiFiManager *myWiFiManager) {
  cetakLCD(0, "SETTING WIFI HP ");
  cetakLCD(1, myWiFiManager->getConfigPortalSSID());
  bunyiBuzzer(3, 150); 
}

void tampilkanJam() {
  if (!rtcAvailable) {
    cetakLCD(0, "RTC HARDWARE ERR");
    cetakLCD(1, " CEK KABEL I2C  ");
    return;
  }
  DateTime now = rtc.now();
  char barisBawah[20];
  sprintf(barisBawah, "%02d:%02d %02d/%02d/%04d", now.hour(), now.minute(), now.day(), now.month(), now.year()); 
  cetakLCD(0, "TEMPELKAN JARI  ");
  cetakLCD(1, String(barisBawah));
}

void syncWaktuNTP() {
  if (!rtcAvailable) return;
  configTime(gmtOffset_sec, daylightOffset_sec, ntpServer);
  struct tm timeinfo;
  if (getLocalTime(&timeinfo, 10000)) { 
    rtc.adjust(DateTime(timeinfo.tm_year + 1900, timeinfo.tm_mon + 1, timeinfo.tm_mday, timeinfo.tm_hour, timeinfo.tm_min, timeinfo.tm_sec));
  }
}

int getFingerprintID() {
  if (!mySerial) return 0;
  uint8_t p = finger.getImage();
  if (p == FINGERPRINT_NOFINGER) return 0; 
  lcdState = 4; 
  cetakLCD(0, " SEDANG PROSES  ");
  cetakLCD(1, " MOHON TUNGGU...");
  
  if (p != FINGERPRINT_OK) { lcdState = 0; return 0; } 
  p = finger.image2Tz();
  if (p != FINGERPRINT_OK) return -1; 
  p = finger.fingerSearch();
  if (p == FINGERPRINT_OK) return finger.fingerID; 
  else return -1; 
}

// ================= FUNGSI PRESENSI & COMMANDS =================

void kirimPresensi(int id, String timeStr) {
  cetakLCD(0, " MENGIRIM DATA  ");
  cetakLCD(1, " >>>>>>>>>>>>>  "); 

  // 1. KIRIM KE LARAVEL TERLEBIH DAHULU
  HTTPClient http;
  String targetUrl = baseUrl + "/presensi";
  http.setTimeout(3000); 
  http.begin(targetUrl);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("ngrok-skip-browser-warning", "69420"); // Bypass ngrok warning

  StaticJsonDocument<256> doc; 
  doc["fingerprint_id"] = id;
  doc["waktu_absen"] = timeStr; 
  doc["device_token"] = deviceToken;
  
  String requestBody; serializeJson(doc, requestBody);
  int httpResponseCode = http.POST(requestBody);
  String response = http.getString();
  
  // 2. CEK RESPONS LARAVEL
  if (httpResponseCode == 200 || httpResponseCode == 201) {
    // --- JIKA SUKSES, BARU KIRIM KE FIREBASE ---
    if (Firebase.ready()) {
      FirebaseJson jsonFirebase;
      jsonFirebase.set("fingerprint_id", id);
      jsonFirebase.set("waktu_absen", timeStr);
      jsonFirebase.set("device_token", deviceToken);

      String path = "/presensi_logs/" + deviceToken;
      if (Firebase.RTDB.pushJSON(&fbdo, path.c_str(), &jsonFirebase)) {
        Serial.println("[FIREBASE] Data berhasil diteruskan ke Firebase setelah sukses Laravel.");
      } else {
        Serial.println("[FIREBASE] Gagal presensi ke Firebase: " + fbdo.errorReason());
      }
    }

    // Tampilkan notifikasi sukses ke LCD
    StaticJsonDocument<512> resDoc; 
    deserializeJson(resDoc, response);
    String nama = resDoc["nama"].as<String>();
    nama.toUpperCase(); 
    cetakLCD(0, "HALO, " + nama); 
    cetakLCD(1, "ABSEN BERHASIL! ");
    bunyiBuzzer(1, 300); 
    
  } else if (httpResponseCode == 400) {
    StaticJsonDocument<512> resDoc;
    deserializeJson(resDoc, response);
    String errorMsg = resDoc["error"].as<String>();
    if (errorMsg == "null" || errorMsg == "") errorMsg = "ABSENSI DITOLAK";
    errorMsg.toUpperCase();
    cetakLCD(0, "ABSENSI REJECTED");
    cetakLCD(1, errorMsg);
    bunyiBuzzer(2, 150); 
  } else {
    cetakLCD(0, "  SERVER ERROR  ");
    cetakLCD(1, " GAGAL KONEKSI  ");
    bunyiBuzzer(3, 80);
  }
  
  http.end();
  lcdState = 3; 
  lcdTimer = millis();
}

void cekDataFirebase() {
  String commandPath = "/commands/" + deviceToken + "/mode";
  
  if (Firebase.RTDB.getString(&fbdo, commandPath.c_str())) {
    String mode = fbdo.stringData();
    
    if (mode == "enroll" && fingerAvailable) { 
      String idPath = "/commands/" + deviceToken + "/fingerprint_id";
      if (Firebase.RTDB.getInt(&fbdo, idPath.c_str())) {
        int enrollId = fbdo.intData();
        prosesEnroll(enrollId);
        
        // Hapus command setelah diproses
        Firebase.RTDB.deleteNode(&fbdo, ("/commands/" + deviceToken).c_str());
      }
    } 
    else if (mode == "delete" && fingerAvailable) {
      String idPath = "/commands/" + deviceToken + "/fingerprint_id";
      String cmdIdPath = "/commands/" + deviceToken + "/command_id";
      
      if (Firebase.RTDB.getInt(&fbdo, idPath.c_str())) {
        int delId = fbdo.intData();
        int commandId = 0;
        if (Firebase.RTDB.getInt(&fbdo, cmdIdPath.c_str())) commandId = fbdo.intData();
        
        prosesHapus(delId, commandId);
        Firebase.RTDB.deleteNode(&fbdo, ("/commands/" + deviceToken).c_str());
      }
    }
    else if (mode == "sync" && fingerAvailable) {
      String idPath = "/commands/" + deviceToken + "/fingerprint_id";
      String hexPath = "/commands/" + deviceToken + "/pola_sidik_jari";
      
      if (Firebase.RTDB.getInt(&fbdo, idPath.c_str())) {
        int syncId = fbdo.intData();
        if (Firebase.RTDB.getString(&fbdo, hexPath.c_str())) {
          String polaHex = fbdo.stringData();
          prosesSync(syncId, polaHex);
        }
        Firebase.RTDB.deleteNode(&fbdo, ("/commands/" + deviceToken).c_str());
      }
    }
    else if (mode == "wipe_database" && fingerAvailable) {
      cetakLCD(0, " FORMAT SENSOR! ");
      cetakLCD(1, " MENGHAPUS DATA ");
      bunyiBuzzer(1, 1000); 
      finger.emptyDatabase();
      cetakLCD(1, " FORMAT SELESAI");
      delay(2000);
      Firebase.RTDB.deleteNode(&fbdo, ("/commands/" + deviceToken).c_str());
    }
    else if (mode == "reset_wifi") {
      cetakLCD(0, " RESET REQ WIFI ");
      cetakLCD(1, " RESTARTING...  ");
      bunyiBuzzer(5, 100); 
      Firebase.RTDB.deleteNode(&fbdo, ("/commands/" + deviceToken).c_str());
      delay(1000); 
      
      WiFiManager wm_temp;
      wm_temp.resetSettings(); 
      delay(1000);
      ESP.restart(); 
    }
  }
}

void cekStatusServer() {
  HTTPClient http;
  String targetUrl = baseUrl + "/cek-status-alat?device_token=" + deviceToken;
  http.setTimeout(3000); 
  http.begin(targetUrl);
  http.addHeader("ngrok-skip-browser-warning", "69420"); // Bypass ngrok warning
  int httpResponseCode = http.GET();
  
  if (httpResponseCode == 200) { 
    String response = http.getString();
    StaticJsonDocument<256> doc; 
    DeserializationError error = deserializeJson(doc, response);

    if (!error && doc.containsKey("mode")) {
      String mode = doc["mode"].as<String>();
      if (mode == "enroll" && fingerAvailable) { 
        int enrollId = doc["fingerprint_id"].as<int>();
        prosesEnroll(enrollId);
        lastPollTime = millis(); 
      } 
      else if (mode == "delete" && fingerAvailable) {
        int delId = doc["fingerprint_id"].as<int>();
        int commandId = doc["command_id"].as<int>(); 
        prosesHapus(delId, commandId); 
        lastPollTime = millis();
      }
      else if (mode == "wipe_database" && fingerAvailable) {
        int commandId = doc["command_id"].as<int>();
        cetakLCD(0, " FORMAT SENSOR! ");
        cetakLCD(1, " MENGHAPUS DATA ");
        bunyiBuzzer(1, 1000); 
        finger.emptyDatabase();
        cetakLCD(1, " FORMAT SELESAI");
        delay(2000);
        konfirmasiHapusServer(0, commandId); 
        lastPollTime = millis();
      }
      else if (mode == "reset_wifi") {
        int commandId = doc["command_id"].as<int>();
        cetakLCD(0, " RESET REQ WIFI ");
        cetakLCD(1, " RESTARTING...  ");
        bunyiBuzzer(5, 100); 
        konfirmasiHapusServer(0, commandId); 
        delay(1000); 
        WiFiManager wm_temp;
        wm_temp.resetSettings(); 
        delay(1000);
        ESP.restart(); 
      }
    }
  }
  http.end();
}

// ================= FUNGSI LOGIKA SIDIK JARI =================

String getFingerprintTemplateHex() {
  uint8_t p = finger.getModel();
  if (p != FINGERPRINT_OK) return "";
  String templateStr = "";
  uint32_t startTimeout = millis();
  delay(100); 
  while ((millis() - startTimeout < 1500) && templateStr.length() < 1024) {
    while (mySerial.available()) {
      uint8_t c = mySerial.read();
      if (c < 0x10) templateStr += "0";
      templateStr += String(c, HEX);
    }
    yield();
  }
  templateStr.toUpperCase();
  if (templateStr.length() > 0) return templateStr;
  else return "";
}

void prosesEnroll(int id) {
  if (!fingerAvailable) return;
  cetakLCD(0, "REGISTRASI JARI ");
  cetakLCD(1, "ID TARGET: " + String(id));
  delay(1500);

  int p = -1; unsigned long startWait = millis();
  cetakLCD(0, "TEMPELKAN JARI  ");
  cetakLCD(1, " TAHAP 1 DARI 2 ");
  while (p != FINGERPRINT_OK) {
    p = finger.getImage(); yield(); delay(50);
    if (millis() - startWait > 15000) { 
      cetakLCD(0, " TIMEOUT ERROR  ");
      cetakLCD(1, "BATAL REGISTRASI");
      konfirmasiEnrollServer(id, "", "failed");
      delay(2000); return; 
    }
  }
  p = finger.image2Tz(1); if (p != FINGERPRINT_OK) return;
  
  p = finger.fingerSearch();
  if (p == FINGERPRINT_OK) { 
    cetakLCD(0, "JARI SUDAH ADA! ");
    cetakLCD(1, "MILIK ID: " + String(finger.fingerID));
    bunyiBuzzer(4, 150); 
    konfirmasiEnrollServer(id, "", "failed");
    delay(2000); return; 
  }

  cetakLCD(0, " ANGKAT JARI... ");
  cetakLCD(1, "                ");
  bunyiBuzzer(1, 200); delay(1500);
  
  cetakLCD(0, " TEMPELKAN LAGI ");
  cetakLCD(1, " TAHAP 2 DARI 2 ");
  p = -1; startWait = millis();
  while (p != FINGERPRINT_OK) {
    p = finger.getImage(); yield(); delay(50);
    if (millis() - startWait > 15000) { konfirmasiEnrollServer(id, "", "failed"); return; }
  }
  
  p = finger.image2Tz(2); if (p != FINGERPRINT_OK) return;
  p = finger.createModel();
  if (p == FINGERPRINT_OK) {
    String polaHex = getFingerprintTemplateHex();
    p = finger.storeModel(id);
    if (p == FINGERPRINT_OK) {
      cetakLCD(0, "REGISTRASI SUKS ");
      cetakLCD(1, "ID AKTIF & DSMPN"); 
      bunyiBuzzer(1, 1000); 
      if (polaHex != "") konfirmasiEnrollServer(id, polaHex, "success"); 
      else konfirmasiEnrollServer(id, "", "failed");
      delay(2000);
    }
  } else {
    cetakLCD(0, "REGISTRASI GAGAL");
    cetakLCD(1, "JARI TDK IDENTIK");
    bunyiBuzzer(3, 200);
    konfirmasiEnrollServer(id, "", "failed"); 
    delay(2000);
  }
}

void prosesHapus(int id, int commandId) { 
  if (!fingerAvailable) return;
  uint8_t p = finger.deleteModel(id);
  if (p == FINGERPRINT_OK) {
    cetakLCD(0, " HAPUS SUKSES!  ");
    cetakLCD(1, "ID: " + String(id) + " TERHAPUS");
    bunyiBuzzer(1, 800); 
    konfirmasiHapusServer(id, commandId); 
  }
  delay(2000);
}

void konfirmasiEnrollServer(int id, String polaHex, String status) {
  // 1. KIRIM KONFIRMASI KE LARAVEL TERLEBIH DAHULU
  HTTPClient http; 
  String targetUrl = baseUrl + "/konfirmasi-enroll"; 
  http.setTimeout(5000); 
  http.begin(targetUrl); 
  http.addHeader("Content-Type", "application/json");
  http.addHeader("ngrok-skip-browser-warning", "69420"); // Bypass ngrok warning
  
  DynamicJsonDocument doc(2048); 
  doc["fingerprint_id"] = id;
  doc["status"] = status; 
  doc["device_token"] = deviceToken;
  if (status == "success") doc["pola_sidik_jari"] = polaHex;
  
  String requestBody; serializeJson(doc, requestBody);
  int httpResponseCode = http.POST(requestBody); 
  
  // 2. JIKA LARAVEL SUKSES, KIRIM KONFIRMASI KE FIREBASE
  if (httpResponseCode == 200 || httpResponseCode == 201) {
    if (Firebase.ready()) {
      FirebaseJson jsonFirebase;
      jsonFirebase.set("fingerprint_id", id);
      jsonFirebase.set("status", status);
      jsonFirebase.set("device_token", deviceToken);
      
      if (status == "success") {
        jsonFirebase.set("pola_sidik_jari", polaHex);
      }
      
      String path = "/enroll_responses/" + deviceToken;
      if (Firebase.RTDB.pushJSON(&fbdo, path.c_str(), &jsonFirebase)) {
        Serial.println("[FIREBASE] Konfirmasi Enroll TERKIRIM");
      } else {
        Serial.println("[FIREBASE] Konfirmasi Enroll GAGAL: " + fbdo.errorReason());
      }
    }
  }
  
  http.end();
}

void konfirmasiHapusServer(int id, int commandId) { 
  // 1. KIRIM KONFIRMASI KE LARAVEL TERLEBIH DAHULU
  HTTPClient http; 
  String targetUrl = baseUrl + "/konfirmasi-hapus";
  http.setTimeout(4000); 
  http.begin(targetUrl); 
  http.addHeader("Content-Type", "application/json");
  http.addHeader("ngrok-skip-browser-warning", "69420"); // Bypass ngrok warning
  
  StaticJsonDocument<256> doc; 
  doc["fingerprint_id"] = id; 
  doc["command_id"] = commandId; 
  doc["status"] = "success"; 
  doc["device_token"] = deviceToken;
  
  String requestBody; serializeJson(doc, requestBody);
  int httpResponseCode = http.POST(requestBody); 
  
  // 2. JIKA LARAVEL SUKSES, KIRIM KONFIRMASI KE FIREBASE
  if (httpResponseCode == 200 || httpResponseCode == 201) {
    if (Firebase.ready()) {
      FirebaseJson jsonFirebase;
      jsonFirebase.set("fingerprint_id", id);
      jsonFirebase.set("command_id", commandId);
      jsonFirebase.set("status", "success");
      jsonFirebase.set("device_token", deviceToken);

      String path = "/hapus_responses/" + deviceToken;
      if (Firebase.RTDB.pushJSON(&fbdo, path.c_str(), &jsonFirebase)) {
        Serial.println("[FIREBASE] Konfirmasi Hapus TERKIRIM");
      } else {
        Serial.println("[FIREBASE] Konfirmasi Hapus GAGAL: " + fbdo.errorReason());
      }
    }
  }
  
  http.end();
}

void prosesSync(int id, String polaHex) {
  if (!fingerAvailable) return;
  if (polaHex.length() != 1024) {
    Serial.println("[SYNC] ERROR: Panjang HEX tidak valid (Harus 1024)");
    return;
  }
  
  cetakLCD(0, "SYNC JARI MASUK ");
  cetakLCD(1, "ID: " + String(id) + " WAIT...");
  Serial.println("\n[SYNC] Memulai Sinkronisasi Jari ID: " + String(id));
  
  // 1. Mengubah HEX 1024 char menjadi Byte Array 512
  uint8_t templateBytes[512];
  for (int i = 0; i < 512; i++) {
    String byteStr = polaHex.substring(i*2, i*2+2);
    templateBytes[i] = (uint8_t) strtol(byteStr.c_str(), NULL, 16);
  }
  
  // 2. MENGIRIM PERINTAH DOWNCHAR (0x09) ke Buffer 1 (0x01)
  uint8_t downCharPacket[] = {0xEF, 0x01, 0xFF, 0xFF, 0xFF, 0xFF, 0x01, 0x00, 0x04, 0x09, 0x01, 0x00, 0x0F};
  mySerial.write(downCharPacket, sizeof(downCharPacket));
  
  delay(50);
  while(mySerial.available()) mySerial.read(); // Kosongkan buffer sementara
  
  // 3. MENGIRIM 512 BYTES DALAM BENTUK DATA PACKETS
  int offset = 0;
  for (int packetNum = 0; packetNum < 4; packetNum++) {
    uint8_t dataPacket[139]; 
    dataPacket[0] = 0xEF; dataPacket[1] = 0x01; // Header
    dataPacket[2] = 0xFF; dataPacket[3] = 0xFF; dataPacket[4] = 0xFF; dataPacket[5] = 0xFF; // Address
    
    // Packet type: 0x02 data, 0x08 end of data
    dataPacket[6] = (packetNum == 3) ? 0x08 : 0x02; 
    
    // Length (128 data + 2 checksum) = 130 (0x00, 0x82)
    dataPacket[7] = 0x00; dataPacket[8] = 0x82;
    
    uint16_t checksum = dataPacket[6] + dataPacket[7] + dataPacket[8];
    
    // 128 bytes data
    for (int i = 0; i < 128; i++) {
      dataPacket[9 + i] = templateBytes[offset++];
      checksum += dataPacket[9 + i];
    }
    
    // Checksum
    dataPacket[137] = (checksum >> 8) & 0xFF;
    dataPacket[138] = checksum & 0xFF;
    
    mySerial.write(dataPacket, 139);
    delay(50);
  }
  
  // 4. SIMPAN BUFFER 1 KE FLASH MEMORY
  uint8_t p = finger.storeModel(id);
  if (p == FINGERPRINT_OK) {
    Serial.println("[SYNC] BERHASIL menyimpan template ke ID " + String(id));
    cetakLCD(0, " SYNC BERHASIL! ");
    cetakLCD(1, " TERSIMPAN: " + String(id));
    bunyiBuzzer(2, 100);
  } else {
    Serial.println("[SYNC] GAGAL menyimpan template.");
    cetakLCD(0, " SYNC GAGAL!    ");
    cetakLCD(1, " KODE ERR: " + String(p));
    bunyiBuzzer(3, 200);
  }
  delay(2000);
}

void bunyiBuzzer(int jumlah, int durasi) {
  for (int i = 0; i < jumlah; i++) {
    digitalWrite(BUZZER_PIN, HIGH); delay(durasi);
    digitalWrite(BUZZER_PIN, LOW ); delay(durasi);
  }
}