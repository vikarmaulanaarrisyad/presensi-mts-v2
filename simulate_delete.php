<?php
// Script untuk Mensimulasikan Konfirmasi Hapus Jari dari ESP32
// File: simulate_delete.php

// Konfigurasi Alat
$baseUrl = 'http://localhost:8000/api'; 
$deviceToken = 'mREzmHaKONQmBtks4ly6bimPM2XnGAtP'; // Token database

// ID Sidik Jari Siswa yang dihapus
$fingerprint_id = 1; 

echo "=================================================\n";
echo "   SIMULASI KONFIRMASI HAPUS JARI ESP32 KE CLOUD\n";
echo "=================================================\n";

// 1. URL Endpoint API Laravel
$apiUrl = $baseUrl . '/konfirmasi-hapus';

// 2. Data Payload (Parameter)
$postData = [
    'device_token' => $deviceToken,
    'fingerprint_id' => $fingerprint_id,
];

echo "\n[1] Menyiapkan Payload Data:\n";
print_r($postData);

// 3. Konfigurasi cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
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
echo "Cek database tabel siswas apakah fingerprint_id siswa target telah menjadi NULL.\n";
echo "=================================================\n";
