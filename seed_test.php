<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::table('devices')->insert(['nama_alat'=>'Alat 1', 'device_token'=>'mREzmHaKONQmBtks4ly6bimPM2XnGAtP']);
DB::table('siswas')->insert(['id'=>155, 'name'=>'Siswa Test', 'nis'=>'12345', 'fingerprint_id'=>'155']);
DB::table('attendance_schedules')->insert(['start_masuk'=>'06:00:00', 'batas_terlambat'=>'07:00:00', 'end_masuk'=>'12:00:00', 'start_pulang'=>'13:00:00', 'end_pulang'=>'20:00:00']);

echo "Done\n";
