<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Kreait\Firebase\Contract\Database;
use App\Models\Siswa;  // <-- TAMBAH INI
use App\Models\Device; // <-- TAMBAH INI
use Yajra\DataTables\Facades\DataTables;

class SiswaController extends Controller
{
    // Cek login helper
    private function cekLogin()
    {
        if (!session('logged_in')) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu!');
        }
        return null;
    }

    public function peta()
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        return view('peta');
    }

    /**
     * Dashboard Monitoring — halaman utama setelah login (Optimized)
     */
    public function index()
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $role = session('user_role'); // Admin, Kepsek, atau Murid

        if ($role === 'murid' || $role === 'siswa') {
            $siswas = DB::table('siswas')->where('name', session('user_name'))->get();
        } else {
            $siswas = DB::table('siswas')->get();
        }

        $hariIni = Carbon::today('Asia/Jakarta');
        $totalSiswa = count($siswas); 
        if ($role === 'murid' || $role === 'siswa') {
            $totalSiswa = DB::table('siswas')->count();
        }

        $rekapHariIni = DB::table('attendances')
            ->select('status_masuk as status', DB::raw('count(*) as total'))
            ->where('tanggal', $hariIni->toDateString())
            ->groupBy('status_masuk')
            ->pluck('total', 'status');

        $totalHadir = $rekapHariIni->get('Hadir', 0);
        $totalIzin  = $rekapHariIni->get('Izin', 0) + $rekapHariIni->get('Sakit', 0);
        $totalAlpa  = $totalSiswa - $totalHadir - $totalIzin;
        if ($totalAlpa < 0) $totalAlpa = 0;

        $logAbsensi = DB::table('attendances')
            ->join('siswas', 'attendances.siswa_id', '=', 'siswas.id')
            ->select(
                'attendances.*',
                'siswas.id as siswa_id', 
                'siswas.name as nama_siswa', 
                'siswas.nis', 
                'siswas.kelas'
            )
            ->where('attendances.tanggal', $hariIni->toDateString())
            ->orderBy('attendances.waktu_masuk', 'desc')
            ->get();

        $daftarKelas = DB::table('siswas')
            ->select('kelas')
            ->distinct()
            ->whereNotNull('kelas')
            ->orderBy('kelas')
            ->get();

        $premiumFeaturesList = DB::table('premium_features')->get();
        $pendingPayments = DB::table('payment_transactions')->where('status', 'pending')->pluck('feature_id')->toArray();
        foreach ($premiumFeaturesList as $f) {
            $f->payment_requested = in_array($f->id, $pendingPayments);
        }
        $premiumFeatures = $premiumFeaturesList->keyBy('menu_code');
        $payments = DB::table('payment_settings')->get();

        if (request()->is('dashboard-admin')) {
            return view('dashboard_admin', compact('premiumFeatures', 'payments', 'totalSiswa', 'totalHadir', 'totalIzin', 'totalAlpa'));
        }

        if (session('user_role') === 'murid') {
            $siswaData = DB::table('siswas')->where('id', session('user_id'))->first();
            $totalHadirKu = DB::table('attendances')->where('siswa_id', session('user_id'))->where('status_masuk', 'Hadir')->count();
            $totalIzinKu = DB::table('attendances')->where('siswa_id', session('user_id'))->whereIn('status_masuk', ['Izin', 'Sakit'])->count();
            $totalAlpaKu = DB::table('attendances')->where('siswa_id', session('user_id'))->where('status_masuk', 'Alpa')->count();
            $logAbsensiKu = DB::table('attendances')->where('siswa_id', session('user_id'))->orderBy('tanggal', 'desc')->limit(20)->get();
            return view('dashboard_siswa', compact('siswaData', 'totalHadirKu', 'totalIzinKu', 'totalAlpaKu', 'logAbsensiKu'));
        }

        if ((session('user_role') === 'guru' || session('user_role') === 'kepsek') && request('view') !== 'full') {
            $guruData = DB::table('users')->where('id', session('user_id'))->first();
            return view('dashboard_guru', compact('guruData', 'totalSiswa', 'totalHadir', 'totalIzin', 'totalAlpa', 'logAbsensi'));
        }

        return view('monitoring', compact(
            'siswas', 'totalSiswa', 'totalHadir', 'totalIzin', 'totalAlpa', 
            'logAbsensi', 'role', 'daftarKelas', 'premiumFeatures'
        ));
    }

    /**
     * Tambah data siswa baru - UDAH PAKE MODEL
     */
    public function store(Request $request)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        if (session('user_role') !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Admin yang bisa menambah data siswa.');
        }

        $request->validate([
            'name'      => 'required|string|max:255',
            'nisn'      => 'required|string|max:50|unique:siswas,nis',
            'kelas'     => 'required',
            'no_wa'     => 'required|string|max:20', 
        ]);

        try {
            $noWaInput = $request->no_wa;

            $siswa = Siswa::create([
                'nis'            => $request->nisn,
                'name'           => $request->name,
                'kelas'          => $request->kelas,
                'password'       => bcrypt($request->nisn),
                'whatsapp'       => $noWaInput,
                'no_wa'          => $noWaInput,
                'role'           => 'murid',
                'fingerprint_id' => null, // Dikosongkan dulu, direkam terpisah
            ]);

            $msg = "Siswa {$request->name} berhasil disimpan. Silakan klik tombol Rekam Jari untuk mendaftarkan sidik jari.";
            if (request()->ajax()) return response()->json(['status' => 'success', 'message' => $msg]);
            return redirect('/data-siswa')->with('success', $msg);
        } catch (\Exception $e) {
            $msg = 'Gagal menyimpan data: ' . $e->getMessage();
            if (request()->ajax()) return response()->json(['status' => 'error', 'message' => $msg], 500);
            return redirect('/data-siswa')->with('error', $msg);
        }
    }

    /**
     * FUNGSI BARU: Mengupdate data siswa
     */
    public function update(Request $request, $id)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        if (session('user_role') !== 'kepsek' && session('user_role') !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'name'  => 'required|string|max:255',
            'nisn'  => 'required|string|max:50',
            'kelas' => 'required|string|max:50',
            'no_wa' => 'required|string|max:20',
        ]);

        DB::table('siswas')->where('id', $id)->update([
            'name'  => $request->name,
            'nis'   => $request->nisn,
            'kelas' => $request->kelas,
            'no_wa' => $request->no_wa,
            'updated_at' => now(),
        ]);

        $msg = "Data siswa {$request->name} berhasil diperbarui!";
        if (request()->ajax()) return response()->json(['status' => 'success', 'message' => $msg]);
        return redirect('/data-siswa')->with('success', $msg);
    }

    /**
     * FUNGSI BARU: Memicu alat untuk merekam sidik jari siswa spesifik
     */
    public function rekamJari(Request $request)
    {
        $request->validate([
            'siswa_id'  => 'required|exists:siswas,id',
            'device_id' => 'required|exists:devices,id',
        ]);

        $siswa = Siswa::findOrFail($request->siswa_id);
        $device = Device::findOrFail($request->device_id);

        // Generate ID baru jika belum punya
        if (!$siswa->fingerprint_id) {
            $nextFingerprintId = (Siswa::max('fingerprint_id') ?? 0) + 1;
        } else {
            $nextFingerprintId = $siswa->fingerprint_id;
        }

        // Cek apakah alat sedang sibuk memproses siswa lain
        if ($device->status === 'enroll' || $device->status === 'delete') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => "Alat {$device->nama_alat} sedang sibuk memproses jari siswa lain. Harap tunggu!"], 422);
            }
            return redirect('/data-siswa')->with('error', "Alat {$device->nama_alat} sedang sibuk! Silakan tunggu sampai proses sebelumnya selesai.");
        }

        DB::table('devices')->where('id', $device->id)->update([
            'status' => 'enroll',
            'target_siswa_id' => $siswa->id,
            'target_fingerprint_id' => $nextFingerprintId
        ]);

        try {
            app('firebase.database')->getReference('commands/' . $device->device_token)->set([
                'mode' => 'enroll',
                'fingerprint_id' => $nextFingerprintId,
                'timestamp' => now()->timestamp
            ]);
        } catch (\Exception $e) {}

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Perintah rekam jari terkirim.']);
        }

        return redirect('/data-siswa')->with('success', "Perintah Rekam Jari (ID: {$nextFingerprintId}) untuk {$siswa->name} terkirim ke alat {$device->nama_alat}. Segera tempelkan jari!");
    }

    /**
     * FUNGSI BARU: Sinkronisasi (Broadcast) Pola Sidik Jari ke Semua Alat ESP32
     */
    public function syncFingerprint(Request $request, $id)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        if (!in_array(session('user_role'), ['admin', 'kepsek'])) {
            $msg = 'Hanya Admin dan Kepsek yang boleh mensinkronisasi data jari.';
            if ($request->ajax()) return response()->json(['status' => 'error', 'message' => $msg], 403);
            return back()->with('error', $msg);
        }

        $siswa = Siswa::findOrFail($id);
        
        if (!$siswa->fingerprint_id || !$siswa->pola_sidik_jari) {
            $msg = "Siswa {$siswa->name} belum merekam jarinya atau data polanya kosong!";
            if ($request->ajax()) return response()->json(['status' => 'error', 'message' => $msg], 422);
            return back()->with('error', $msg);
        }

        // Cari semua alat yang aktif
        $devices = Device::all();
        if ($devices->count() == 0) {
            $msg = 'Tidak ada alat absensi yang terdaftar.';
            if ($request->ajax()) return response()->json(['status' => 'error', 'message' => $msg], 404);
            return back()->with('error', $msg);
        }

        $count = 0;
        foreach ($devices as $device) {
            try {
                // Broadcast ke masing-masing alat melalui Firebase
                app('firebase.database')->getReference('commands/' . $device->device_token)->set([
                    'mode' => 'sync',
                    'fingerprint_id' => $siswa->fingerprint_id,
                    'pola_sidik_jari' => $siswa->pola_sidik_jari,
                    'timestamp' => now()->timestamp
                ]);
                $count++;
            } catch (\Exception $e) {
                // Abaikan error per alat
            }
        }

        $msg = "Proses Sinkronisasi Jari untuk {$siswa->name} dikirim ke {$count} alat!";
        if ($request->ajax()) return response()->json(['status' => 'success', 'message' => $msg]);
        return back()->with('success', $msg);
    }

    /**
     * FUNGSI BARU: Memicu alat untuk menghapus sidik jari spesifik
     */
    public function hapusJariAlat(Request $request)
    {
        $request->validate([
            'siswa_id'  => 'required|exists:siswas,id',
            'device_id' => 'required|exists:devices,id',
        ]);

        $siswa = Siswa::findOrFail($request->siswa_id);
        $device = Device::findOrFail($request->device_id);

        if (!$siswa->fingerprint_id) {
            return redirect('/data-siswa')->with('error', 'Siswa ini belum memiliki data sidik jari!');
        }

        // Cek apakah alat sedang sibuk memproses siswa lain
        if ($device->status === 'enroll' || $device->status === 'delete') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => "Alat {$device->nama_alat} sedang sibuk memproses jari siswa lain. Harap tunggu!"], 422);
            }
            return redirect('/data-siswa')->with('error', "Alat {$device->nama_alat} sedang sibuk! Silakan tunggu sampai proses sebelumnya selesai.");
        }

        DB::table('devices')->where('id', $device->id)->update([
            'status' => 'delete',
            'target_siswa_id' => $siswa->id,
            'target_fingerprint_id' => $siswa->fingerprint_id
        ]);

        try {
            app('firebase.database')->getReference('commands/' . $device->device_token)->set([
                'mode' => 'delete',
                'fingerprint_id' => $siswa->fingerprint_id,
                'command_id' => time(),
                'timestamp' => now()->timestamp
            ]);
        } catch (\Exception $e) {}

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => "Perintah Hapus Jari untuk {$siswa->name} terkirim ke alat {$device->nama_alat}."]);
        }
        return redirect('/data-siswa')->with('success', "Perintah Hapus Jari untuk {$siswa->name} terkirim ke alat {$device->nama_alat}.");
    }

    /**
     * FUNGSI BARU: Reset sidik jari hanya di lokal DB
     */
    public function resetJariLokal($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->update(['fingerprint_id' => null]);
        
        if (request()->ajax()) {
            return response()->json(['status' => 'success', 'message' => "Data sidik jari {$siswa->name} berhasil di-reset dari database lokal."]);
        }
        return redirect('/data-siswa')->with('success', "Data sidik jari {$siswa->name} berhasil di-reset dari database lokal.");
    }

    /**
     * Hapus data siswa
     */
    public function destroy($id)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        if (session('user_role') !== 'admin') {
            if (request()->ajax()) return response()->json(['status' => 'error', 'message' => 'Akses ditolak! Hanya Admin yang bisa menghapus data siswa.'], 403);
            return redirect('/data-siswa')->with('error', 'Akses ditolak! Hanya Admin yang bisa menghapus data siswa.');
        }

        $siswa = DB::table('siswas')->where('id', $id)->first();
        if (!$siswa) {
            if (request()->ajax()) return response()->json(['status' => 'error', 'message' => 'Data siswa tidak ditemukan!'], 404);
            return redirect('/data-siswa')->with('error', 'Data siswa tidak ditemukan!');
        }

        // Jika siswa memiliki sidik jari, perintahkan semua alat ESP32 untuk menghapusnya
        if ($siswa->fingerprint_id) {
            DB::table('devices')->update([
                'status' => 'delete',
                'target_siswa_id' => $siswa->id,
                'target_fingerprint_id' => $siswa->fingerprint_id
            ]);

            try {
                $devices = DB::table('devices')->get();
                $fb = app('firebase.database');
                foreach($devices as $dev) {
                    if($dev->device_token) {
                        $fb->getReference('commands/' . $dev->device_token)->set([
                            'mode' => 'delete',
                            'fingerprint_id' => $siswa->fingerprint_id,
                            'command_id' => time(),
                            'timestamp' => now()->timestamp
                        ]);
                    }
                }
            } catch (\Exception $e) {}
        }

        DB::table('siswas')->where('id', $id)->delete();
        
        $msg = 'Data siswa ' . $siswa->name . ' berhasil dihapus dari database cloud dan perintah hapus jari telah dikirim ke ESP32!';
        if (request()->ajax()) {
            return response()->json(['status' => 'success', 'message' => $msg]);
        }
        return redirect('/data-siswa')->with('success', $msg);
    }

    /**
     * Notifikasi tidak hadir via WhatsApp Fonnte
     */
    public function notif_wa($id)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $siswa  = DB::table('siswas')->where('id', $id)->first();
        if (!$siswa) return redirect('/data-siswa')->with('error', 'Siswa tidak ditemukan!');

        $nomorWa = $siswa->no_wa ?? $siswa->whatsapp;

        if (!$nomorWa || $nomorWa == '081234567890') {
            $msg = 'Notifikasi WhatsApp dibatalkan karena nomor siswa ' . $siswa->name . ' masih kosong atau bernilai default.';
            if (request()->ajax()) return response()->json(['status' => 'warning', 'message' => $msg]);
            return redirect('/data-siswa')->with('warning', $msg);
        }

        $apiKey = env('FONNTE_API_KEY', '8LX8ds8EW1jz8QHQzaid');
        $pesan  = "⚠️ *PEMBERITAHUAN KETIDAKHADIRAN*\n\nYth. Bapak/Ibu orang tua dari Ananda *{$siswa->name}*\n\nAnanda hari ini terdeteksi *TIDAK HADIR* di sekolah tanpa keterangan.\nMohon segera konfirmasi ke pihak sekolah.\n\n📅 Tanggal: " . Carbon::now('Asia/Jakarta')->format('d-m-Y') . "\n🏫 MTs Mambaul Ulum Kota Tegal";

        try {
            Http::withHeaders(['Authorization' => $apiKey])
                ->withoutVerifying()
                ->post('https://api.fonnte.com/send', [
                    'target'  => $nomorWa,
                    'message' => $pesan,
                ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim WhatsApp Fonnte: ' . $e->getMessage());
        }

        $msg = 'Notifikasi WhatsApp berhasil dikirim ke orang tua ' . $siswa->name;
        if (request()->ajax()) return response()->json(['status' => 'success', 'message' => $msg]);
        return redirect('/data-siswa')->with('success', $msg);
    }

    /**
     * Halaman Data Siswa - UDAH KIRIM $devices
     */
    public function dataSiswa()
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $role = session('user_role');
        if ($role === 'murid' || $role === 'siswa') {
            $siswas = DB::table('siswas')->where('name', session('user_name'))->get();
        } else {
            $siswas = DB::table('siswas')->get();
        }

        $devices = Device::orderBy('nama_alat')->get(); 
        $activeEnrolls = DB::table('devices')->where('status', 'enroll')->pluck('id', 'target_siswa_id')->toArray();
        $kelases = DB::table('kelas')->orderBy('nama_kelas')->get();
        $premiumFeatures = DB::table('premium_features')->get()->keyBy('menu_code');
        $payments = DB::table('payment_settings')->get();

        return view('data-siswa', compact('siswas', 'role', 'devices', 'activeEnrolls', 'kelases', 'premiumFeatures', 'payments'));
    }

    /**
     * DataTables endpoint untuk Data Siswa
     */
    public function dataSiswaDatatable(Request $request)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return response()->json(['error' => 'Unauthorized'], 401);

        $role = session('user_role');
        
        $query = DB::table('siswas');
        if ($role === 'murid' || $role === 'siswa') {
            $query->where('name', session('user_name'));
        }

        $activeEnrolls = DB::table('devices')->where('status', 'enroll')->pluck('id', 'target_siswa_id')->toArray();
        $premiumFeatures = DB::table('premium_features')->get()->keyBy('menu_code');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('name', function ($siswa) {
                return $siswa->name ?? 'Nama Tidak Ada';
            })
            ->editColumn('nis', function ($siswa) {
                return $siswa->nis ?? '-';
            })
            ->editColumn('kelas', function ($siswa) {
                return '<span class="badge bg-secondary bg-opacity-10 text-secondary border px-2.5 py-1.5 fw-semibold">' . ($siswa->kelas ?? 'VII - A') . '</span>';
            })
            ->editColumn('fingerprint_id', function ($siswa) {
                if ($siswa->fingerprint_id) {
                    return '<span class="text-success font-mono-custom fw-bold"><i class="fa-solid fa-fingerprint me-1"></i> ID-' . $siswa->fingerprint_id . '</span>';
                } else {
                    return '<span class="badge bg-danger bg-opacity-10 text-danger border px-2 py-1 fw-semibold" style="font-size: 0.75rem;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Belum</span>';
                }
            })
            ->addColumn('aksi', function ($siswa) use ($premiumFeatures, $activeEnrolls) {
                return view('partials.siswa_aksi', compact('siswa', 'premiumFeatures', 'activeEnrolls'))->render();
            })
            ->rawColumns(['kelas', 'fingerprint_id', 'aksi'])
            ->make(true);
    }

    // ... fungsi dataKelas, storeKelas, simpanIzin, rekapPdf, tesFirebase TETAP SAMA ...
    /**
     * Halaman Data Kelas
     */
    public function dataKelas()
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $kelases = DB::table('kelas')
            ->leftJoin('siswas', 'kelas.nama_kelas', '=', 'siswas.kelas')
            ->select(
                'kelas.id',
                'kelas.nama_kelas',
                'kelas.id_ruang',
                'kelas.wali_kelas',
                'kelas.kapasitas',
                DB::raw('COUNT(siswas.id) as jumlah_siswa')
            )
            ->groupBy('kelas.id', 'kelas.nama_kelas', 'kelas.id_ruang', 'kelas.wali_kelas', 'kelas.kapasitas')
            ->orderBy('kelas.nama_kelas')
            ->get();

        $hariIni = Carbon::today('Asia/Jakarta');
        foreach ($kelases as $kls) {
            $siswaIdList = DB::table('siswas')->where('kelas', $kls->nama_kelas)->pluck('id');
            $totalSiswaKelas = count($siswaIdList);
            if ($totalSiswaKelas === 0) {
                $kls->persentase_hadir = 0;
            } else {
                $hadirHariIni = DB::table('attendances')
                    ->whereIn('siswa_id', $siswaIdList)
                    ->whereDate('created_at', $hariIni)
                    ->where('status', 'Hadir')
                    ->count();
                $kls->persentase_hadir = round(($hadirHariIni / $totalSiswaKelas) * 100);
            }
        }

        $siswas = DB::table('siswas')->get();
        $role   = session('user_role');

        return view('data-kelas', compact('kelases', 'siswas', 'role'));
    }

    public function storeKelas(Request $request)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;
        if (session('user_role') !== 'kepsek' && session('user_role') !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }
        $request->validate([
            'nama_kelas' => 'required|string|max:20|unique:kelas,nama_kelas',
            'id_ruang'   => 'required|string|max:20',
            'wali_kelas' => 'required|string|max:100',
            'kapasitas'  => 'required|integer|min:1|max:50',
        ]);
        DB::table('kelas')->insert([
            'nama_kelas' => $request->nama_kelas,
            'id_ruang'   => $request->id_ruang,
            'wali_kelas' => $request->wali_kelas,
            'kapasitas'  => $request->kapasitas,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function updateKelas(Request $request, $id)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;
        if (session('user_role') !== 'kepsek' && session('user_role') !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'nama_kelas' => 'required|string|max:20|unique:kelas,nama_kelas,' . $id,
            'id_ruang'   => 'required|string|max:20',
            'wali_kelas' => 'required|string|max:100',
            'kapasitas'  => 'required|integer|min:1|max:50',
        ]);

        $kelasLama = DB::table('kelas')->where('id', $id)->first();
        if (!$kelasLama) return redirect()->back()->with('error', 'Kelas tidak ditemukan!');

        DB::table('kelas')->where('id', $id)->update([
            'nama_kelas' => $request->nama_kelas,
            'id_ruang'   => $request->id_ruang,
            'wali_kelas' => $request->wali_kelas,
            'kapasitas'  => $request->kapasitas,
            'updated_at' => now(),
        ]);

        // Update nama kelas di tabel siswas jika nama kelas berubah
        if ($kelasLama->nama_kelas !== $request->nama_kelas) {
            DB::table('siswas')->where('kelas', $kelasLama->nama_kelas)->update([
                'kelas' => $request->nama_kelas
            ]);
        }

        return redirect()->back()->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function deleteKelas($id)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;
        if (session('user_role') !== 'kepsek' && session('user_role') !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $kelas = DB::table('kelas')->where('id', $id)->first();
        if (!$kelas) return redirect()->back()->with('error', 'Kelas tidak ditemukan!');

        // Hapus kelas (Bisa tambahkan logika apakah ingin mengosongkan nama kelas pada siswa jika diperlukan)
        // DB::table('siswas')->where('kelas', $kelas->nama_kelas)->update(['kelas' => null]);
        
        DB::table('kelas')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus!');
    }

    public function simpanIzin(Request $request)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;
        $request->validate([
            'siswa_id'   => 'required|integer',
            'keterangan' => 'required|string|max:255',
            'status'     => 'required|in:Izin,Sakit,Alpa',
        ]);
        $tanggalHariIni = Carbon::today('Asia/Jakarta')->toDateString();
        
        DB::table('attendances')->updateOrInsert(
            ['siswa_id' => $request->siswa_id, 'tanggal' => $tanggalHariIni],
            [
                'status_masuk' => $request->status,
                'keterangan_masuk' => $request->keterangan,
                'sumber' => 'Manual',
                'updated_at' => Carbon::now('Asia/Jakarta')
            ]
        );
        
        $siswa = DB::table('siswas')->where('id', $request->siswa_id)->first();
        return redirect()->back()->with('success', 'Data ' . $request->status . ' untuk ' . ($siswa->name ?? '') . ' berhasil disimpan!');
    }

    public function laporanIndex()
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $daftarKelas = DB::table('siswas')->select('kelas')->distinct()->whereNotNull('kelas')->orderBy('kelas')->get();
        return view('laporan', compact('daftarKelas'));
    }

    public function rekapPdf(Request $request)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;
        
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $kelas     = $request->get('kelas', '');
        
        $query = DB::table('attendances')->join('siswas', 'attendances.siswa_id', '=', 'siswas.id')
            ->select('attendances.*', 'siswas.name as nama_siswa', 'siswas.nis', 'siswas.kelas')
            ->whereDate('attendances.tanggal', '>=', $startDate)
            ->whereDate('attendances.tanggal', '<=', $endDate);
            
        if ($kelas) { 
            $query->where('siswas.kelas', $kelas); 
        }
        
        $dataAbsensi  = $query->orderBy('siswas.kelas')->orderBy('siswas.name')->get();
        $totalHadir   = $dataAbsensi->whereIn('status_masuk', ['Hadir', 'Masuk', 'Terlambat'])->count();
        $totalIzin    = $dataAbsensi->whereIn('status_masuk', ['Izin', 'Sakit'])->count();
        $totalAlpa    = $dataAbsensi->where('status_masuk', 'Alpa')->count();
        
        $periodeFormat = Carbon::parse($startDate)->locale('id')->isoFormat('DD MMMM YYYY') . ' s/d ' . Carbon::parse($endDate)->locale('id')->isoFormat('DD MMMM YYYY');
        
        $pdfData = compact('dataAbsensi', 'startDate', 'endDate', 'kelas', 'totalHadir', 'totalIzin', 'totalAlpa', 'periodeFormat');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('rekap_pdf', $pdfData);
        $pdf->setPaper('a4', 'landscape');
        
        $namaFile = 'Rekap_Absensi_MTs_' . ($kelas ?: 'Semua') . '_' . $startDate . '_sd_' . $endDate . '.pdf';
        return $pdf->download($namaFile);
    }

    public function tesFirebase(Database $database)
    {
        $database->getReference('tes_koneksi')->set([
            'pesan' => 'Halo Firebase, saya Laravel dari MTs Mambaul Ulum!',
            'status' => 'TERHUBUNG 100%',
            'waktu_koneksi' => Carbon::now('Asia/Jakarta')->toDateTimeString()
        ]);
        return "Koneksi Sukses! Sekarang coba cek halaman Realtime Database lu di Google Chrome, datanya pasti otomatis muncul tanpa perlu di-refresh.";
    }

    public function pengajuanIzinSiswa(Request $request)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        if (session('user_role') !== 'murid') {
            return redirect()->back()->with('error', 'Hanya siswa yang dapat melakukan pengajuan absen dari menu ini.');
        }

        $request->validate([
            'status'     => 'required|in:Izin,Sakit',
            'keterangan' => 'required|string|max:255',
        ]);

        $siswaId = session('user_id');
        $hariIni = Carbon::today('Asia/Jakarta')->toDateString();

        $sudahAda = DB::table('attendances')->where('siswa_id', $siswaId)->where('tanggal', $hariIni)->exists();

        if ($sudahAda) {
            return redirect()->back()->with('error', 'Anda sudah memiliki catatan presensi atau pengajuan izin untuk hari ini!');
        }

        DB::table('attendances')->insert([
            'siswa_id'   => $siswaId,
            'tanggal'    => $hariIni,
            'status_masuk'     => $request->status,
            'keterangan_masuk' => $request->keterangan,
            'sumber' => 'Manual',
            'created_at' => Carbon::now('Asia/Jakarta'),
            'updated_at' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()->back()->with('success', 'Pengajuan ' . $request->status . ' Anda berhasil dikirim ke Admin!');
    }
}