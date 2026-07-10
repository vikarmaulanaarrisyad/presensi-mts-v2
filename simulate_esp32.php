<?php

// Konfigurasi Alat
$baseUrl = 'http://localhost:8000/api'; 
$deviceToken = 'mREzmHaKONQmBtks4ly6bimPM2XnGAtP'; // Token database
$firebaseUrl = 'https://presensimts-80d6a-default-rtdb.asia-southeast1.firebasedatabase.app';

// ID Sidik Jari Siswa yang ingin dites (Pastikan ID ini ada di tabel siswas!)
$fingerprint_id = 2; 

// Waktu absen bisa diedit secara manual di sini untuk mengetes skenario jam masuk / di luar jam.
// Contoh: '2026-07-07 07:00:00' (Jam Masuk valid)
// Biarkan date('Y-m-d H:i:s') untuk mengetes menggunakan jam server Anda saat ini.
$waktu_absen = '2026-07-07 07:15:00'; // Kita set ke jam 07:15 pagi agar dianggap valid saat dicoba


echo "=== SIMULATOR ESP32 ===\n";
echo "Menyimulasikan tap sidik jari dengan ID: $fingerprint_id pada $waktu_absen\n\n";

// 1. KIRIM KE LARAVEL API
$laravelUrl = $baseUrl . '/presensi';
$laravelData = json_encode([
    'fingerprint_id' => $fingerprint_id,
    'waktu_absen'    => $waktu_absen,
    'device_token'   => $deviceToken
]);

echo "[1] Mengirim data ke API Laravel ($laravelUrl)...\n";
$ch = curl_init($laravelUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $laravelData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
$laravelResponse = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo " -> Response Code: $httpCode\n";
echo " -> Response Body: $laravelResponse\n\n";

// 2. JIKA BERHASIL (200 / 201), BARU KIRIM KE FIREBASE
if ($httpCode == 200 || $httpCode == 201) {
    echo "[2] Absensi diterima oleh Laravel. Sekarang mengirim 'Push Sinyal' ke Firebase...\n";
    
    // Path Firebase sesuai dengan logika ESP32
    $firebasePath = $firebaseUrl . "/presensi_logs/" . $deviceToken . ".json";
    
    $firebaseData = json_encode([
        'fingerprint_id' => $fingerprint_id,
        'waktu_absen'    => $waktu_absen,
        'device_token'   => $deviceToken
    ]);

    $chFb = curl_init($firebasePath);
    curl_setopt($chFb, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chFb, CURLOPT_POST, true);
    curl_setopt($chFb, CURLOPT_POSTFIELDS, $firebaseData);
    curl_setopt($chFb, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    $firebaseResponse = curl_exec($chFb);
    curl_close($chFb);
    
    echo " -> Firebase Signal Terkirim!\n";
    echo " -> Coba cek halaman Monitoring Real-Time di browser Anda, tabel seharusnya otomatis ter-update.\n";
} else {
    echo "[2] Absensi DITOLAK oleh Laravel (Mungkin bukan jam absen). Tidak mengirim sinyal ke Firebase.\n";
}

echo "\nSelesai.\n";
