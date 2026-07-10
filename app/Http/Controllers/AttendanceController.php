<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Kreait\Firebase\Contract\Database;

class AttendanceController extends Controller
{
    /**
     * =========================================================================
     * FUNGSI HALAMAN UTAMA MONITORING (WEB)
     * =========================================================================
     */
    public function index()
    {
        $logAbsensi = DB::table('siswas')
            ->leftJoin('attendances', 'siswas.id', '=', 'attendances.siswa_id')
            ->select('siswas.id as siswa_id', 'siswas.nis', 'siswas.name as nama_siswa', 'siswas.kelas', 'attendances.status', 'attendances.keterangan')
            ->get();

        $totalSiswa = DB::table('siswas')->count();
        $totalHadir = DB::table('attendances')->where('status', 'Hadir')->whereDate('created_at', Carbon::today('Asia/Jakarta'))->count();
        $totalIzin  = DB::table('attendances')->whereIn('status', ['Izin', 'Sakit'])->whereDate('created_at', Carbon::today('Asia/Jakarta'))->count();

        return view('monitoring', compact('logAbsensi', 'totalSiswa', 'totalHadir', 'totalIzin'));
    }

    /**
     * =========================================================================
     * 1. POST /api/presensi — TERIMA DATA PRESENSI DARI ALAT ESP32
     * =========================================================================
     */
    public function kirimPresensi(Request $request, Database $database)
    {
        // Validasi Token Rahasia Alat (Sudah ditangani secara global oleh Middleware ValidateDeviceToken)
        $token = $request->input('device_token');

        $fingerprintId = $request->input('fingerprint_id');
        if (!$fingerprintId) {
            return response()->json(['status' => 'error', 'message' => 'fingerprint_id wajib diisi.'], 400);
        }

        $siswa = DB::table('siswas')->where('fingerprint_id', $fingerprintId)->first();
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'ID Sidik Jari tidak terdaftar.'], 404);
        }

        // Jika ada waktu_absen dari request (misal dari simulator ESP32), gunakan itu. Jika tidak, pakai waktu server.
        $waktuAbsenReq = $request->input('waktu_absen');
        if ($waktuAbsenReq) {
            $waktu = Carbon::parse($waktuAbsenReq, 'Asia/Jakarta');
        } else {
            $waktu = Carbon::now('Asia/Jakarta');
        }
        $jamSekarang = $waktu->format('H:i:s');

        // AMBIL JADWAL
        $schedule = \App\Models\AttendanceSchedule::first();
        if (!$schedule) {
            return response()->json(['status' => 'error', 'message' => 'Jadwal absen belum diatur admin.'], 400);
        }

        $statusAbsen = '';
        $keteranganAbsen = '';

        // FUNGSI BANTUAN UNTUK CEK RENTANG WAKTU (Mendukung beda hari/overnight)
        $isTimeBetween = function($time, $start, $end) {
            if ($start <= $end) {
                return $time >= $start && $time <= $end;
            } else {
                // Lewat tengah malam (contoh: start 19:00, end 02:00)
                return $time >= $start || $time <= $end;
            }
        };

        $tanggalHariIni = Carbon::today('Asia/Jakarta')->toDateString();

        // CEK APAKAH DALAM JAM MASUK
        if ($isTimeBetween($jamSekarang, $schedule->start_masuk, $schedule->end_masuk)) {
            $sudahMasuk = DB::table('attendances')
                ->where('siswa_id', $siswa->id)
                ->where('tanggal', $tanggalHariIni)
                ->whereNotNull('waktu_masuk')
                ->exists();

            if ($sudahMasuk) {
                return response()->json([
                    'status'  => 'already',
                    'message' => $siswa->name . ' sudah absen masuk hari ini.',
                    'nama'    => $siswa->name,
                    'jenis_absen' => 'Masuk'
                ], 200);
            }

            $statusAbsen = 'Masuk';
            
            // Cek keterlambatan (mendukung overnight)
            $isTerlambat = false;
            if ($schedule->start_masuk <= $schedule->batas_terlambat) {
                $isTerlambat = $jamSekarang > $schedule->batas_terlambat && $jamSekarang <= $schedule->end_masuk;
            } else {
                // Batas terlambat lewat tengah malam
                if ($jamSekarang > $schedule->batas_terlambat && $jamSekarang <= $schedule->end_masuk) {
                    $isTerlambat = true;
                } elseif ($jamSekarang > $schedule->batas_terlambat && $jamSekarang >= $schedule->start_masuk) {
                    $isTerlambat = true;
                }
            }
            
            if ($isTerlambat || (!$isTimeBetween($jamSekarang, $schedule->start_masuk, $schedule->batas_terlambat) && $isTimeBetween($jamSekarang, $schedule->start_masuk, $schedule->end_masuk))) {
                $keteranganAbsen = 'Terlambat';
            } else {
                $keteranganAbsen = 'Hadir';
            }

            // SIMPAN / UPDATE MASUK
            DB::table('attendances')->updateOrInsert(
                ['siswa_id' => $siswa->id, 'tanggal' => $tanggalHariIni],
                [
                    'waktu_masuk' => $waktu->format('H:i:s'),
                    'status_masuk' => 'Hadir', // Status umum, keterangan yang bilang hadir/terlambat
                    'keterangan_masuk' => $keteranganAbsen,
                    'sumber' => 'IoT',
                    'updated_at' => now('Asia/Jakarta')
                ]
            );

        } 
        // CEK APAKAH DALAM JAM PULANG
        else if ($isTimeBetween($jamSekarang, $schedule->start_pulang, $schedule->end_pulang)) {
            $sudahPulang = DB::table('attendances')
                ->where('siswa_id', $siswa->id)
                ->where('tanggal', $tanggalHariIni)
                ->whereNotNull('waktu_pulang')
                ->exists();

            if ($sudahPulang) {
                return response()->json([
                    'status'  => 'already',
                    'message' => $siswa->name . ' sudah absen pulang hari ini.',
                    'nama'    => $siswa->name,
                    'jenis_absen' => 'Pulang'
                ], 200);
            }

            $statusAbsen = 'Pulang';
            $keteranganAbsen = 'Pulang';

            // SIMPAN / UPDATE PULANG
            DB::table('attendances')->updateOrInsert(
                ['siswa_id' => $siswa->id, 'tanggal' => $tanggalHariIni],
                [
                    'waktu_pulang' => $waktu->format('H:i:s'),
                    'status_pulang' => 'Hadir',
                    'keterangan_pulang' => 'Pulang',
                    'sumber' => 'IoT',
                    'updated_at' => now('Asia/Jakarta')
                ]
            );
        } 
        // DI LUAR JAM ABSEN
        else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Belum waktunya absen masuk atau pulang.',
                'nama'    => $siswa->name
            ], 400);
        }

        // Fitur WhatsApp Notifikasi via Fonnte
        if (!empty($siswa->whatsapp)) {
            $apiKey = env('FONNTE_API_KEY', '8LX8ds8EW1jz8QHQzaid');
            $pesan  = "✅ *PRESENSI HADIR FINGERPRINT*\n\nAnanda *{$siswa->name}* telah melakukan absensi di MTs Mambaul Ulum Kota Tegal.\n🕐 Pukul: " . $waktu->format('H:i') . " WIB\n📅 Tanggal: " . $waktu->format('d-m-Y') . "\n🏫 Kelas: " . ($siswa->kelas ?? '-');

            if ($apiKey) {
                try {
                    \Illuminate\Support\Facades\Http::withHeaders(['Authorization' => $apiKey])
                        ->withoutVerifying()->post('https://api.fonnte.com/send', [
                            'target'  => $siswa->whatsapp,
                            'message' => $pesan,
                        ]);
                } catch (\Exception $e) {}
            }
        }

        // Firebase Push Real-time
        try {
            app('firebase.database')->getReference('scan_fingerprint')->set([
                'siswa_id' => $siswa->id,
                'nis' => $siswa->nis ?? '-',
                'nama_siswa' => $siswa->name,
                'kelas' => $siswa->kelas ?? '-',
                'status' => $statusAbsen,
                'keterangan' => $keteranganAbsen,
                'waktu' => $waktu->format('H:i'), // Cukup jam dan menit
                'timestamp' => $waktu->timestamp
            ]);
        } catch (\Exception $e) {
            \Log::error('[Firebase Absensi Push Error] ' . $e->getMessage());
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Presensi berhasil disimpan ke cloud!',
            'nama'    => $siswa->name,
            'waktu'   => $waktu->format('H:i:s'),
            'jenis_absen' => $statusAbsen
        ], 200);
    }

    /**
     * =========================================================================
     * 2. GET /api/cek-status-alat — POLLING CEK PERINTAH DARI WEB KE ALAT (5 DETIK)
     * =========================================================================
     */
   /**
     * =========================================================================
     * FUNGSI API CEK STATUS (SYNCHRONIZED): Disamakan dengan logika token & ID otomatis
     * =========================================================================
     */
    public function cekStatusServer(Request $request)
    {
        // 1. KEAMANAN: Cari alat berdasarkan device_token yang dikirim oleh ESP32
        $device = DB::table('devices')->where('device_token', $request->device_token)->first();
        
        // Jika token salah atau alat tidak terdaftar, langsung kunci akses
        if (!$device) {
            return response()->json([
                'status' => 'error',
                'message' => 'Device Token tidak valid!'
            ], 401);
        }

        // 1.5 Update Waktu Terakhir ESP32 Terhubung (last_ping)
        DB::table('devices')->where('id', $device->id)->update([
            'last_ping' => now('Asia/Jakarta')
        ]);

        // Default mode jika status di database adalah 'scan' (tidak ada instruksi khusus dari admin)
        $mode = 'scan'; 
        $targetId = 0;

        // 2. LOGIKA ENROLL ATAU DELETE KHUSUS ATAU LOCKDOWN
        if ($device->status === 'enroll' || $device->status === 'delete' || $device->status === 'lock') {
            $mode = $device->status;
            // Ambil ID dari target yang sudah diset oleh web sebelumnya (jika ada)
            $targetId = $device->target_fingerprint_id ?? 0; 
        } 
        
        // 4. RESPONSE JSON: Kirimkan mode dan ID target ke ESP32
        return response()->json([
            'status'         => 'success',
            'mode'           => $mode,
            'fingerprint_id' => $targetId
        ], 200);
    }

    /**
     * =========================================================================
     * 3. POST /api/konfirmasi-enroll — BACKUP DATA POLA JARI SETELAH DAFTAR SUKSES
     * =========================================================================
     */
    public function konfirmasiEnrollServer(Request $request)
    {
        $token = $request->input('device_token');
        $device = DB::table('devices')->where('device_token', $token)->first();
        if (!$device) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized Device Token.'], 401);
        }

        $fingerprintId = $request->input('fingerprint_id');
        $status = $request->input('status');
        $polaSidikJari = $request->input('pola_sidik_jari'); // Kode HEX Sidik jari dari alat

        if ($status === 'success' && $device->target_siswa_id) {
            // Update data siswa masukkan id fingerprint dan backup string polanya ke database cloud
            DB::table('siswas')
                ->where('id', $device->target_siswa_id)
                ->update([
                    'fingerprint_id' => $fingerprintId,
                    'pola_sidik_jari' => $polaSidikJari, // Simpan pola HEX
                    'updated_at' => Carbon::now('Asia/Jakarta')
                ]);
        }
                
        try {
            app('firebase.database')->getReference('enroll_responses/' . $device->device_token)->set([
                'status' => $status,
                'siswa_id' => $device->target_siswa_id,
                'timestamp' => Carbon::now('Asia/Jakarta')->timestamp
            ]);
        } catch (\Exception $e) {
            \Log::error('[Firebase Enroll Push Error] ' . $e->getMessage());
        }

        // Kembalikan status mesin ke mode standby normal dan bersihkan target
        DB::table('devices')->where('id', $device->id)->update([
            'status' => 'scan',
            'target_siswa_id' => null,
            'target_fingerprint_id' => null
        ]);

        try {
            app('firebase.database')->getReference('commands/' . $device->device_token)->remove();
        } catch (\Exception $e) {}

        return response()->json(['status' => 'success', 'message' => 'Konfirmasi enroll berhasil disimpan.'], 200);
    }

    /**
     * =========================================================================
     * 4. POST /api/konfirmasi-hapus — KONFIRMASI PENGHAPUSAN SIDIK JARI SUKSES
     * =========================================================================
     */
    public function konfirmasiHapusServer(Request $request)
    {
        $token = $request->input('device_token');
        $device = DB::table('devices')->where('device_token', $token)->first();
        if (!$device) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized Device Token.'], 401);
        }

        $fingerprintId = $request->input('fingerprint_id');
        $status = $request->input('status');

        // Bersihkan id sidik jari siswa di database lokal berdasarkan target alat
        if ($status === 'success' && $device->target_siswa_id) {
            DB::table('siswas')
                ->where('id', $device->target_siswa_id)
                ->update([
                    'fingerprint_id' => null,
                    'pola_sidik_jari' => null,
                    'updated_at' => Carbon::now('Asia/Jakarta')
                ]);
                
            try {
                app('firebase.database')->getReference('delete_responses/' . $device->device_token)->set([
                    'status' => 'success',
                    'siswa_id' => $device->target_siswa_id,
                    'timestamp' => Carbon::now('Asia/Jakarta')->timestamp
                ]);
            } catch (\Exception $e) {}
        }

        // Kembalikan status mesin ke mode standby normal dan bersihkan target
        DB::table('devices')->where('id', $device->id)->update([
            'status' => 'scan',
            'target_siswa_id' => null,
            'target_fingerprint_id' => null
        ]);

        try {
            app('firebase.database')->getReference('commands/' . $device->device_token)->remove();
        } catch (\Exception $e) {}

        return response()->json(['status' => 'success', 'message' => 'Konfirmasi hapus berhasil.'], 200);
    }

    /**
     * Konfirmasi dari ESP32 setelah menyelesaikan perintah delete_all (reset semua jari)
     */
    public function konfirmasiResetSemuaJari(Request $request)
    {
        $token = $request->input('device_token');
        $device = DB::table('devices')->where('device_token', $token)->first();
        if (!$device) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized Device Token.'], 401);
        }

        $totalDihapus = $request->input('total_dihapus', 0);
        $totalGagal   = $request->input('total_gagal', 0);
        $status       = $request->input('status', 'success');

        \Log::info("[RESET_SEMUA_JARI] Alat: {$device->nama_alat} | Token: {$token} | Berhasil: {$totalDihapus} | Gagal: {$totalGagal}");

        // Reset status alat ke standby
        DB::table('devices')->where('id', $device->id)->update([
            'status'               => 'scan',
            'target_siswa_id'      => null,
            'target_fingerprint_id'=> null,
        ]);

        // Tulis notifikasi ke Firebase agar semua browser yang membuka data-siswa ikut reload
        try {
            app('firebase.database')->getReference('reset_all_responses/' . $token)->set([
                'status'        => ($totalGagal == 0) ? 'success' : 'partial',
                'type'          => $status,
                'total_dihapus' => (int) $totalDihapus,
                'total_gagal'   => (int) $totalGagal,
                'nama_alat'     => $device->nama_alat,
                'timestamp'     => now()->timestamp,
            ]);
            \Log::info("[RESET_SEMUA_JARI] Notifikasi Firebase terkirim ke reset_all_responses/{$token}");
        } catch (\Exception $e) {
            \Log::warning("[RESET_SEMUA_JARI] Gagal kirim notifikasi Firebase: " . $e->getMessage());
        }

        return response()->json([
            'status'         => 'ok',
            'message'        => "Konfirmasi reset semua jari diterima. Berhasil: {$totalDihapus}, Gagal: {$totalGagal}",
            'total_dihapus'  => $totalDihapus,
            'total_gagal'    => $totalGagal,
        ], 200);
    }

    /**
     * =========================================================================
     * INPUT PRESENSI MANUAL OLEH ADMIN DARI WEB
     * =========================================================================
     */
    public function storeManual(Request $request)
    {
        $request->validate([
            'siswa_id'   => 'required',
            'status'     => 'required',
            'keterangan' => 'nullable|string'
        ]);

        $siswaId    = $request->input('siswa_id');
        $status     = ucfirst($request->input('status')); 
        $keterangan = $request->input('keterangan') ?? '-';
        $waktu      = Carbon::now('Asia/Jakarta');

        $siswa = DB::table('siswas')->where('id', $siswaId)->first();
        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan!');
        }

        DB::table('attendances')
            ->where('siswa_id', $siswaId)
            ->whereDate('created_at', Carbon::today('Asia/Jakarta'))
            ->delete();

        DB::table('attendances')->insert([
            'siswa_id'   => $siswaId,
            'status'     => $status,
            'keterangan' => $keterangan,
            'time_in'    => $waktu, 
            'created_at' => $waktu,
            'updated_at' => $waktu,
        ]);

        // WhatsApp Notif Manual
        if (!empty($siswa->whatsapp)) {
            $apiKey = env('FONNTE_API_KEY', '8LX8ds8EW1jz8QHQzaid');

            if ($status == 'Sakit') {
                $icon = "🤢 *PEMBERITAHUAN SAKIT*";
                $detailStatus = "tidak dapat mengikuti KBM hari ini karena *Sakit* (Ket: {$keterangan}).";
            } elseif ($status == 'Izin') {
                $icon = "✉️ *PEMBERITAHUAN IZIN*";
                $detailStatus = "tidak dapat mengikuti KBM hari ini karena *Izin* (Ket: {$keterangan}).";
            } else {
                $icon = "⚠️ *PEMBERITAHUAN TIDAK MASUK (ALPA)*";
                $detailStatus = "berdasarkan rekapitulasi jam pelajaran pertama hari ini dinyatakan *Alpa / Tanpa Keterangan*.";
            }

            $pesan = "{$icon}\n\nAssalamu'alaikum wr. wb.\nMenginfokan kepada Orang Tua/Wali dari siswa bernama *{$siswa->name}* (Kelas: " . ($siswa->kelas ?? '-') . "), bahwa hari ini:\n📅 Tanggal: " . $waktu->format('d-m-Y') . "\n\nAnanda {$detailStatus}\n\nDemikian pemberitahuan ini kami sampaikan. Terima kasih.\n🏫 *MTs Mambaul Ulum Kota Tegal*";

            if ($apiKey) {
                try {
                    \Illuminate\Support\Facades\Http::withHeaders(['Authorization' => $apiKey])
                        ->withoutVerifying()->post('https://api.fonnte.com/send', [
                            'target'  => $siswa->whatsapp,
                            'message' => $pesan,
                        ]);
                } catch (\Exception $e) {}
            }
        }

        return redirect()->back()->with('success', 'Data absensi manual berhasil disimpan dan notifikasi WA terkirim!');
    }
}