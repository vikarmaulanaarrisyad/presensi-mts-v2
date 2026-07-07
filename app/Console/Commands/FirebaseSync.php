<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FirebaseSync extends Command
{
    /**
     * @var string
     */
    protected $signature = 'firebase:sync';

    /**
     * @var string
     */
    protected $description = 'Mendengarkan data ketukan sidik jari dari Firebase Realtime Database dan sinkronisasi otomatis ke MySQL Cloud';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('===========================================================');
        $this->info('🚀 ENGINE JEMBATAN OTOMATIS FIREBASE <-> MYSQL AKTIF...');
        $this->info('📡 Memantau node [history_absensi] secara realtime...');
        $this->info('===========================================================');

        /** @var \Kreait\Firebase\Contract\Database $firebaseDatabase */
        $firebaseDatabase = app('firebase.database');
        $reference = $firebaseDatabase->getReference('history_absensi');

        while (true) {
            $snapshot = $reference->getSnapshot();

            if ($snapshot->exists()) {
                $allLogs = $snapshot->getValue();

                if (is_array($allLogs)) {
                    foreach ($allLogs as $key => $log) {
                        if (isset($log['fingerprint_id']) && isset($log['status'])) {
                            
                            $siswa = DB::table('siswas')->where('fingerprint_id', $log['fingerprint_id'])->first();

                            if ($siswa) {
                                $hariIni = Carbon::today('Asia/Jakarta');

                                $sudahAbsen = DB::table('attendances')
                                    ->where('siswa_id', $siswa->id)
                                    ->whereDate('created_at', $hariIni)
                                    ->exists();

                                if (!$sudahAbsen) {
                                    DB::table('attendances')->insert([
                                        'siswa_id'   => $siswa->id,
                                        'status'     => $log['status'],
                                        'keterangan' => 'Hadir Tepat Waktu (Biometrik otomatis)',
                                        'sumber'     => 'Alat',
                                        'created_at' => Carbon::now('Asia/Jakarta'),
                                        'updated_at' => Carbon::now('Asia/Jakarta')
                                    ]);

                                    $this->info("✨ [SUKSES SINKRON] Absen dimasukkan untuk: {$siswa->name}");
                                } else {
                                    $this->warn("⚠️ [LEWAT] {$siswa->name} sudah tercatat melakukan absensi hari ini.");
                                }

                                $reference->getChild($key)->remove();
                            } else {
                                $this->error("❌ [ERROR] Fingerprint ID {$log['fingerprint_id']} tidak terdaftar pada tabel siswa MySQL.");
                            }
                        }
                    }
                }
            }

            sleep(2);
        }
    }
}