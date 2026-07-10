<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperadminController extends Controller
{
    private function cekLogin()
    {
        if (!session('logged_in') || session('user_role') !== 'superadmin') {
            return redirect('/')->with('error', 'Akses ditolak! Halaman khusus Superadmin.');
        }
        return null;
    }

    public function index()
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $features = DB::table('premium_features')->get();
        $payments = DB::table('payment_settings')->get();
        $pendingPayments = DB::table('payment_transactions')
            ->join('premium_features', 'payment_transactions.feature_id', '=', 'premium_features.id')
            ->select('payment_transactions.*', 'premium_features.nama_fitur')
            ->where('payment_transactions.status', 'pending')
            ->orderBy('payment_transactions.created_at', 'asc')
            ->get();
        
        return view('dashboard_superadmin', compact('features', 'payments', 'pendingPayments'));
    }

    public function update(Request $request)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $request->validate([
            'features' => 'required|array',
            'payments' => 'nullable|array',
            'new_payments' => 'nullable|array',
        ]);

        // Update existing payment settings
        if ($request->has('payments')) {
            foreach ($request->payments as $id => $p) {
                DB::table('payment_settings')->where('id', $id)->update([
                    'nama_bank' => $p['nama_bank'] ?? null,
                    'no_rekening' => $p['no_rekening'] ?? null,
                    'atas_nama' => $p['atas_nama'] ?? null,
                    'updated_at' => now(),
                ]);
            }
        }

        // Insert new payment settings
        if ($request->has('new_payments')) {
            foreach ($request->new_payments as $p) {
                if (!empty($p['nama_bank']) || !empty($p['no_rekening'])) {
                    DB::table('payment_settings')->insert([
                        'nama_bank' => $p['nama_bank'] ?? null,
                        'no_rekening' => $p['no_rekening'] ?? null,
                        'atas_nama' => $p['atas_nama'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Update features
        foreach ($request->features as $id => $data) {
            DB::table('premium_features')->where('id', $id)->update([
                'harga' => $data['harga'] ?? '0',
                'is_active' => isset($data['is_active']) ? true : false,
                'is_unlocked' => isset($data['is_unlocked']) ? true : false,
                'has_demo' => isset($data['has_demo']) ? true : false,
                'max_demo_requests' => $data['max_demo_requests'] ?? 1,
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Pengaturan fitur prabayar dan rekening berhasil diperbarui!');
    }

    public function requestDemo($menu_code)
    {
        // Pastikan hanya admin (atau kepsek) yang bisa request
        if (session('user_role') !== 'admin' && session('user_role') !== 'kepsek') {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $feature = DB::table('premium_features')->where('menu_code', $menu_code)->first();
        if (!$feature) {
            return redirect()->back()->with('error', 'Fitur tidak ditemukan.');
        }

        if (!$feature->has_demo) {
            return redirect()->back()->with('error', 'Superadmin belum mengizinkan pengajuan demo untuk fitur ini.');
        }

        if ($feature->demo_requested) {
            return redirect()->back()->with('error', 'Pengajuan demo sedang diproses. Silakan tunggu.');
        }

        if ($feature->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($feature->demo_expires_at))) {
            return redirect()->back()->with('error', 'Demo untuk fitur ini sedang aktif.');
        }

        if ($feature->demo_used_count >= $feature->max_demo_requests) {
            return redirect()->back()->with('error', 'Batas penggunaan demo (' . $feature->max_demo_requests . ' kali) telah habis.');
        }

        DB::table('premium_features')->where('menu_code', $menu_code)->update([
            'demo_requested' => true,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pengajuan demo berhasil dikirim ke Superadmin. Silakan tunggu persetujuan.');
    }

    public function approveDemo(Request $request, $menu_code)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $request->validate([
            'minutes' => 'required|integer|min:1',
        ]);

        $feature = DB::table('premium_features')->where('menu_code', $menu_code)->first();
        if (!$feature) {
            return redirect()->back()->with('error', 'Fitur tidak ditemukan.');
        }

        if (!$feature->demo_requested) {
            return redirect()->back()->with('error', 'Tidak ada permintaan demo untuk fitur ini.');
        }

        $minutes = (int) $request->minutes;
        $expiresAt = \Carbon\Carbon::now()->addMinutes($minutes);

        DB::table('premium_features')->where('menu_code', $menu_code)->update([
            'demo_expires_at' => $expiresAt,
            'demo_requested' => false,
            'demo_used_count' => DB::raw('demo_used_count + 1'),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', "Permintaan demo disetujui selama {$minutes} menit.");
    }

    public function resetDemo($menu_code)
    {
        $feature = DB::table('premium_features')->where('menu_code', $menu_code)->first();

        if (!$feature) {
            return redirect()->back()->with('error', 'Fitur tidak ditemukan.');
        }

        DB::table('premium_features')->where('menu_code', $menu_code)->update([
            'demo_expires_at' => null,
            'demo_requested' => false,
            'demo_used_count' => 0,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Histori demo untuk fitur ' . $feature->nama_fitur . ' telah di-reset (seperti baru).');
    }

    public function apiPendingDemos()
    {
        $pendingRequests = DB::table('premium_features')
            ->where('demo_requested', true)
            ->get(['menu_code', 'nama_fitur']);

        return response()->json([
            'status' => 'success',
            'data' => $pendingRequests
        ]);
    }

    public function deletePayment($id)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        DB::table('payment_settings')->where('id', $id)->delete();
        return back()->with('success', 'Rekening berhasil dihapus.');
    }

    public function uploadPayment(Request $request, $menu_code)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $feature = DB::table('premium_features')->where('menu_code', $menu_code)->first();
        if (!$feature) return back()->with('error', 'Fitur tidak ditemukan.');

        $file = $request->file('bukti_bayar');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/payments', $filename);

        DB::table('payment_transactions')->insert([
            'feature_id' => $feature->id,
            'bukti_bayar' => $filename,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu konfirmasi Superadmin.');
    }

    public function approvePayment($id)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $transaction = DB::table('payment_transactions')->where('id', $id)->first();
        if (!$transaction) return back()->with('error', 'Transaksi tidak ditemukan.');

        DB::table('payment_transactions')->where('id', $id)->update(['status' => 'approved', 'updated_at' => now()]);
        
        DB::table('premium_features')->where('id', $transaction->feature_id)->update([
            'is_unlocked' => 1,
            'updated_at' => now()
        ]);

        return back()->with('success', 'Pembayaran disetujui, fitur sekarang terbuka permanen.');
    }

    public function rejectPayment($id)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        DB::table('payment_transactions')->where('id', $id)->update(['status' => 'rejected', 'updated_at' => now()]);
        return back()->with('success', 'Pembayaran ditolak.');
    }

    public function monitoring()
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        // ── PHP & Server ────────────────────────────────────────────────
        $phpVersion      = PHP_VERSION;
        $serverSoftware  = $_SERVER['SERVER_SOFTWARE'] ?? 'N/A';
        $osInfo          = PHP_OS_FAMILY . ' (' . PHP_OS . ')';
        $memoryLimit     = ini_get('memory_limit');
        $maxUpload       = ini_get('upload_max_filesize');
        $maxPost         = ini_get('post_max_size');
        $memoryUsage     = round(memory_get_usage(true) / 1024 / 1024, 2); // MB
        $memoryPeak      = round(memory_get_peak_usage(true) / 1024 / 1024, 2);
        $phpExtensions   = implode(', ', array_slice(get_loaded_extensions(), 0, 20));

        // ── Laravel ─────────────────────────────────────────────────────
        $laravelVersion  = app()->version();
        $environment     = app()->environment();
        $timezone        = config('app.timezone');
        $locale          = config('app.locale');
        $cacheDriver     = config('cache.default');
        $sessionDriver   = config('session.driver');
        $queueDriver     = config('queue.default');
        $debugMode       = config('app.debug') ? 'ON (Nonaktifkan di Produksi!)' : 'OFF';

        // ── Disk Usage ───────────────────────────────────────────────────
        $diskTotal       = disk_total_space(base_path());
        $diskFree        = disk_free_space(base_path());
        $diskUsed        = $diskTotal - $diskFree;
        $diskPct         = $diskTotal > 0 ? round(($diskUsed / $diskTotal) * 100, 1) : 0;
        $diskTotalFmt    = round($diskTotal / 1024 / 1024 / 1024, 2) . ' GB';
        $diskFreeFmt     = round($diskFree / 1024 / 1024 / 1024, 2) . ' GB';
        $diskUsedFmt     = round($diskUsed / 1024 / 1024 / 1024, 2) . ' GB';

        // ── Database ────────────────────────────────────────────────────
        $dbName          = config('database.connections.mysql.database');
        $dbVersion       = DB::selectOne('SELECT VERSION() as version')->version ?? 'N/A';
        $dbHost          = config('database.connections.mysql.host');
        $dbPort          = config('database.connections.mysql.port');

        $dbSize = DB::selectOne("
            SELECT ROUND(SUM(DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024, 2) AS size_mb
            FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?", [$dbName]);
        $dbSizeMb = $dbSize->size_mb ?? 0;

        $tables = DB::select("
            SELECT TABLE_NAME as table_name, TABLE_ROWS as table_rows, 
                   ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024, 2) as size_kb
            FROM information_schema.TABLES 
            WHERE TABLE_SCHEMA = ?
            ORDER BY (DATA_LENGTH + INDEX_LENGTH) DESC", [$dbName]);

        // ── Statistik Aplikasi ───────────────────────────────────────────
        $totalSiswa      = DB::table('siswas')->count();
        $totalDevice     = DB::table('devices')->count();
        $totalAttendance = DB::table('attendances')->count();
        $totalFitur      = DB::table('premium_features')->count();
        $totalTransaksi  = DB::table('payment_transactions')->count();
        $pendingTransaksi = DB::table('payment_transactions')->where('status', 'pending')->count();
        $approvedTransaksi = DB::table('payment_transactions')->where('status', 'approved')->count();

        $attendanceToday = DB::table('attendances')->where('tanggal', today()->toDateString())->count();

        // ── Log Files ────────────────────────────────────────────────────
        $logPath = storage_path('logs/laravel.log');
        $logSize = file_exists($logPath) ? round(filesize($logPath) / 1024, 1) . ' KB' : 'N/A';
        $logLastLines = [];
        if (file_exists($logPath)) {
            $lines = file($logPath);
            $logLastLines = array_slice($lines, -10);
        }

        // ── Trend Absensi 7 Hari ────────────────────────────────────────
        $chartLabels = [];
        $chartHadir  = [];
        $chartIzin   = [];
        $chartAlpa   = [];
        for ($i = 6; $i >= 0; $i--) {
            $day   = \Carbon\Carbon::today()->subDays($i);
            $rekap = DB::table('attendances')
                ->select('status_masuk as status', DB::raw('count(*) as total'))
                ->where('tanggal', $day->toDateString())
                ->groupBy('status_masuk')
                ->pluck('total', 'status');

            $chartLabels[] = $day->format('d M');
            $chartHadir[]  = (int) ($rekap->get('Hadir', 0));
            $chartIzin[]   = (int) ($rekap->get('Izin', 0) + $rekap->get('Sakit', 0));
            $chartAlpa[]   = (int) ($rekap->get('Alpa', 0));
        }

        // ── Absensi Terbaru (10 terakhir) ───────────────────────────────
        $recentAttendances = DB::table('attendances')
            ->join('siswas', 'attendances.siswa_id', '=', 'siswas.id')
            ->select('attendances.status_masuk as status', 'attendances.updated_at as created_at', 'siswas.name', 'siswas.kelas')
            ->orderBy('attendances.updated_at', 'desc')
            ->limit(10)
            ->get();

        // ── Perangkat ESP32 + Status ─────────────────────────────────────
        $devices = DB::table('devices')->select('nama_alat', 'last_ping')->orderBy('nama_alat')->get();

        // ── Health Checks ────────────────────────────────────────────────
        $healthChecks = [];

        // DB connection
        try {
            DB::select('SELECT 1');
            $healthChecks[] = ['label' => 'Database MySQL', 'ok' => true, 'info' => 'Terkoneksi'];
        } catch (\Exception $e) {
            $healthChecks[] = ['label' => 'Database MySQL', 'ok' => false, 'info' => 'Gagal: ' . $e->getMessage()];
        }

        // Cache
        try {
            \Illuminate\Support\Facades\Cache::put('health_test', 1, 5);
            $healthChecks[] = ['label' => 'Cache (' . config('cache.default') . ')', 'ok' => true, 'info' => 'Berfungsi'];
        } catch (\Exception $e) {
            $healthChecks[] = ['label' => 'Cache', 'ok' => false, 'info' => 'Error'];
        }

        // Storage link
        $storageLinkOk = file_exists(public_path('storage'));
        $healthChecks[] = ['label' => 'Storage Link', 'ok' => $storageLinkOk, 'info' => $storageLinkOk ? 'Tertaut' : 'Belum dijalankan (php artisan storage:link)'];

        // .env exists
        $envOk = file_exists(base_path('.env'));
        $healthChecks[] = ['label' => 'File .env', 'ok' => $envOk, 'info' => $envOk ? 'Ditemukan' : 'Tidak ada!'];

        // Debug mode
        $debugOff = !config('app.debug');
        $healthChecks[] = ['label' => 'Debug Mode', 'ok' => $debugOff, 'info' => $debugOff ? 'OFF (Aman)' : 'ON — Matikan di produksi!'];

        // Log writable
        $logWritable = is_writable(storage_path('logs'));
        $healthChecks[] = ['label' => 'Log Folder', 'ok' => $logWritable, 'info' => $logWritable ? 'Dapat ditulis' : 'Tidak bisa ditulis!'];

        // Storage writable
        $storageWritable = is_writable(storage_path('app'));
        $healthChecks[] = ['label' => 'Storage Folder', 'ok' => $storageWritable, 'info' => $storageWritable ? 'Dapat ditulis' : 'Tidak bisa ditulis!'];

        // ── Absensi per Kelas (hari ini) ─────────────────────────────────
        $perKelas = DB::table('attendances')
            ->join('siswas', 'attendances.siswa_id', '=', 'siswas.id')
            ->select('siswas.kelas', DB::raw('count(*) as total'))
            ->where('attendances.tanggal', today()->toDateString())
            ->groupBy('siswas.kelas')
            ->orderBy('siswas.kelas')
            ->get();

        return view('superadmin.monitoring', compact(
            'phpVersion', 'serverSoftware', 'osInfo', 'memoryLimit', 'maxUpload', 'maxPost',
            'memoryUsage', 'memoryPeak', 'phpExtensions',
            'laravelVersion', 'environment', 'timezone', 'locale', 'cacheDriver',
            'sessionDriver', 'queueDriver', 'debugMode',
            'diskTotal', 'diskFree', 'diskUsed', 'diskPct', 'diskTotalFmt', 'diskFreeFmt', 'diskUsedFmt',
            'dbName', 'dbVersion', 'dbHost', 'dbPort', 'dbSizeMb', 'tables',
            'totalSiswa', 'totalDevice', 'totalAttendance', 'totalFitur',
            'totalTransaksi', 'pendingTransaksi', 'approvedTransaksi', 'attendanceToday',
            'logSize', 'logLastLines',
            'chartLabels', 'chartHadir', 'chartIzin', 'chartAlpa',
            'recentAttendances', 'devices', 'healthChecks', 'perKelas'
        ));
    }
}
