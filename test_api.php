<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/api/presensi', 'POST', [
    'device_token' => 'mREzmHaKONQmBtks4ly6bimPM2XnGAtP',
    'fingerprint_id' => 1,
    'waktu_absen' => '2026-07-07 10:00:00'
]);
$response = app()->handle($request);
echo $response->getContent();
