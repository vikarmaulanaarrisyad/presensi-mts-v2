<?php

// Konfigurasi Alat
$baseUrl = 'http://localhost:8000/api/fingerprint'; 
$deviceToken = 'mREzmHaKONQmBtks4ly6bimPM2XnGAtP'; // Token database
$firebaseUrl = 'https://presensimts-80d6a-default-rtdb.asia-southeast1.firebasedatabase.app';

// ID Sidik Jari Siswa yang ingin dites (Pastikan ID ini ada di tabel siswas!)
$fingerprint_id = 155; 

// Waktu absen bisa diedit secara manual di sini untuk mengetes skenario jam masuk / di luar jam.
// Contoh: '2026-07-07 07:00:00' (Jam Masuk valid)
// Biarkan date('Y-m-d H:i:s') untuk mengetes menggunakan jam server Anda saat ini.
$waktu_absen = '2026-07-10 07:00:00';


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

// 2. TAMPILKAN STATUS
if ($httpCode == 200 || $httpCode == 201) {
    echo "[2] Absensi berhasil diterima dan disimpan oleh Laravel.\n";
    echo " -> Laravel secara otomatis menembakkan (push) notifikasi realtime ke Firebase.\n";
    echo " -> Coba cek halaman Monitoring Real-Time di browser Anda, tabel seharusnya otomatis ter-update.\n";
} else {
    echo "[2] Absensi DITOLAK oleh Laravel (Mungkin bukan jam absen, atau Token salah).\n";
}

echo "\nSelesai.\n";
