<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\Device; 
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB; // <-- WAJIB: Ditambah untuk membaca instruksi database

class DeviceController extends Controller
{
    // Cek login biar aman
    private function cekLogin()
    {
        if (!session('logged_in')) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu!');
        }
        return null;
    }

    public function index()
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $devices = Device::orderBy('nama_alat')->get();
        $premiumFeaturesList = \Illuminate\Support\Facades\DB::table('premium_features')->get();
        $pendingPayments = \Illuminate\Support\Facades\DB::table('payment_transactions')->where('status', 'pending')->pluck('feature_id')->toArray();
        foreach ($premiumFeaturesList as $f) {
            $f->payment_requested = in_array($f->id, $pendingPayments);
        }
        $premiumFeatures = $premiumFeaturesList->keyBy('menu_code');
        $payment = \Illuminate\Support\Facades\DB::table('payment_settings')->get();
        $payments = $payment; // Keep variable name 'payments' for consistency later

        return view('devices.index', compact('devices', 'premiumFeatures', 'payments'));
    }

    /**
     * =========================================================================
     * FUNGSI STORE (UPDATED): Menambahkan field device_token agar tersimpan ke DB
     * =========================================================================
     */
    /**
     * =========================================================================
     * FUNGSI STORE (UPDATED): Device Token di-generate otomatis secara acak & unik
     * =========================================================================
     */
    public function store(Request $request){
         // Debug: Tampilkan semua data request untuk memastikan inputan diterima
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        if (!in_array(session('user_role'), ['admin', 'kepsek'])) {
            return back()->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengelola alat.');
        }

        // SESUAIKAN: Hapus 'device_token' dari validasi karena inputan tidak lagi dari form
        $request->validate([
            'nama_alat'    => 'required|string|max:100',
             
        ]);
//         'ip_address'   => 'required|ip|unique:devices,ip_address',
        // GENERATE OTOMATIS: Membuat token acak string unik sepanjang 32 karakter
        $generatedToken = \Illuminate\Support\Str::random(32);

        // Menyimpan data ke tabel database termasuk device_token otomatis
        DB::table('devices')->insert([
            'nama_alat'    => $request->nama_alat,
            'ip_address'   => $request->ip_address,
            'device_token' => $generatedToken, // Memasukkan token hasil generate otomatis
            'status'       => 'scan', 
        ]);

        return back()->with('success', 'Alat ' . $request->nama_alat . ' berhasil ditambahkan dengan Token: ' . $generatedToken);
    }

    public function destroy($id)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;
        
        if (!in_array(session('user_role'), ['admin', 'kepsek'])) {
            return back()->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengelola alat.');
        }

        Device::findOrFail($id)->delete();
        return back()->with('success', 'Alat berhasil dihapus.');
    }

    /**
     * =========================================================================
     * FUNGSI PING (FIXED): Menggunakan DB Builder agar fungsi Cek Koneksi juga aman dari updated_at
     * =========================================================================
     */
    public function ping($id){
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $d = Device::findOrFail($id);
        $diff = $d->last_ping ? \Carbon\Carbon::parse($d->last_ping)->diffInSeconds(now('Asia/Jakarta')) : 999;
        
        if ($diff <= 30) {
            $status = 'Terhubung (Online via Sinkronisasi)';
            $is_online = true;
        } else {
            try {
                $res = Http::timeout(3)->get("http://{$d->ip_address}/api/cek");
                $is_online = $res->successful();
                $status = $is_online ? 'Terhubung (Online via IP)' : 'Terputus (Offline)';
            } catch(\Exception $e) { 
                $is_online = false;
                $status = 'Terputus (Offline)'; 
            }
        }

        // UBAH DI SINI: Jangan timpa status jika alat sedang enroll/delete/lock
        $new_status = ($d->status === 'enroll' || $d->status === 'delete' || $d->status === 'lock') ? $d->status : ($is_online ? 'scan' : 'offline');

        DB::table('devices')->where('id', $id)->update([
            'status'    => $new_status,
            'last_ping' => $is_online ? now('Asia/Jakarta') : $d->last_ping,
        ]);

        if ($is_online) {
            return back()->with('success', "TEST KONEKSI SUKSES: Alat {$d->nama_alat} saat ini {$status}");
        } else {
            return back()->with('error', "TEST KONEKSI GAGAL: Alat {$d->nama_alat} saat ini {$status}. Pastikan ESP32 menyala dan terhubung ke WiFi yang sama.");
        }
    }

    /**
     * =========================================================================
     * FUNGSI BARU: Mengembalikan alat ke Mode Scan normal
     * =========================================================================
     */
    public function forceScanMode($id)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        if (!in_array(session('user_role'), ['admin', 'kepsek'])) {
            return back()->with('error', 'Hanya Admin dan Kepsek yang boleh mengontrol alat.');
        }

        $device = Device::findOrFail($id);

        DB::table('devices')->where('id', $id)->update([
            'status' => 'scan',
            'target_siswa_id' => null,
            'target_fingerprint_id' => null
        ]);

        try {
            app('firebase.database')->getReference('commands/' . $device->device_token)->remove();
        } catch (\Exception $e) {}

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Alat berhasil dikembalikan ke Mode Scan']);
        }

        return back()->with('success', 'Alat ' . $device->nama_alat . ' berhasil dikembalikan ke Mode Scan.');
    }

    /**
     * =========================================================================
     * FUNGSI BARU: Mengunci alat (Lockdown)
     * =========================================================================
     */
    public function lockDevice($id)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $d = Device::findOrFail($id);
        
        DB::table('devices')->where('id', $id)->update([
            'status' => 'lock',
            'target_siswa_id' => null,
            'target_fingerprint_id' => null
        ]);

        try {
            app('firebase.database')->getReference('commands/' . $d->device_token)->remove();
        } catch (\Exception $e) {}

        return back()->with('success', "Alat {$d->nama_alat} berhasil dikunci (Lockdown). Alat tidak akan menerima absen masuk/pulang.");
    }
}