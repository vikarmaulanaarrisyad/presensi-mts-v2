<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $hariIni = \Carbon\Carbon::today('Asia/Jakarta');
    
    $result = Illuminate\Support\Facades\DB::table('attendances')
        ->join('siswas', 'attendances.siswa_id', '=', 'siswas.id')
        ->select(
            'attendances.id',
            'attendances.siswa_id',
            'attendances.tanggal',
            'attendances.waktu_masuk',
            'attendances.waktu_pulang',
            'attendances.status_masuk',
            'attendances.keterangan_masuk',
            'attendances.status_pulang',
            'attendances.keterangan_pulang',
            'siswas.name as nama_siswa',
            'siswas.nis',
            'siswas.kelas'
        )
        ->where('attendances.tanggal', $hariIni->toDateString())
        ->orderBy('attendances.waktu_masuk', 'desc')
        ->get();

    echo "Total records: " . count($result) . "\n";
    echo json_encode($result->first(), JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
}
