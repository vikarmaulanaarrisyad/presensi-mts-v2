<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$siswa = new \App\Models\Siswa(); 
$siswa->nis = '123456'; 
$siswa->name = 'Test Firebase Siswa'; 
$siswa->kelas = '7A'; 
$siswa->fingerprint_id = 1; 
$siswa->save(); 
echo "Siswa Dummy Created.\n";
