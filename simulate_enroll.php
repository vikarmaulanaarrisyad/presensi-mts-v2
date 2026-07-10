<?php
// Script untuk Mensimulasikan Konfirmasi Rekam Jari dari ESP32
// File: simulate_enroll.php

// Konfigurasi Alat
$baseUrl = 'http://localhost:8000/api/fingerprint'; 
$deviceToken = 'mREzmHaKONQmBtks4ly6bimPM2XnGAtP'; // Token database

// ID Sidik Jari Siswa yang direkam (Bisa diganti atau pakai random agar tidak duplikat)
// Tabel 'siswas' memiliki UNIQUE constraint pada fingerprint_id, jadi tidak boleh sama dengan siswa lain.
$fingerprint_id = isset($argv[1]) ? (int)$argv[1] : rand(100, 999); 

echo "=================================================\n";
echo "   SIMULASI KONFIRMASI REKAM JARI ESP32 KE CLOUD\n";
echo "=================================================\n";

// 1. URL Endpoint API Laravel
$apiUrl = $baseUrl . '/konfirmasi-enroll';

// 2. Data Payload (Parameter)
// Buat string HEX palsu sepanjang 1024 karakter agar lolos validasi sync ESP32
$dummyHex = str_repeat('0F', 512); // 512 byte = 1024 karakter hex

$postData = [
    'device_token' => $deviceToken,
    'fingerprint_id' => $fingerprint_id,
    'status' => 'success',
    'pola_sidik_jari' => $dummyHex 
];

echo "\n[1] Menyiapkan Payload Data:\n";
print_r($postData);

// 3. Konfigurasi cURL
$jsonData = json_encode($postData);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// 4. Eksekusi Request
echo "\n[2] Mengirim POST Request ke API Laravel ($apiUrl)...\n";
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo "ERROR CURL: " . curl_error($ch) . "\n";
} else {
    echo "HTTP Status Code: " . $httpCode . "\n";
    echo "Response dari Server: \n";
    
    // Coba decode response JSON
    $jsonResponse = json_decode($response, true);
    if ($jsonResponse) {
        echo json_encode($jsonResponse, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo $response . "\n";
    }
}

curl_close($ch);

echo "\n=================================================\n";
echo "Jika sukses, status alat di database kembali ke 'scan' dan Firebase /commands/token dihapus.\n";
echo "Cek database tabel siswas apakah fingerprint_id siswa target berubah menjadi $fingerprint_id.\n";
echo "=================================================\n";
