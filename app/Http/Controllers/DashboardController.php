<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\siswa; 
use App\Models\Attendance;

class DashboardController extends Controller
{
    /**
     * Menampilkan Halaman Utama (Dashboard / Form Login menyatu)
     * OPTIMIZED: Menggunakan eager loading untuk mencegah halaman hang/muter terus
     */
    public function index()
    {
        if (!Auth::check()) {
            return view('dashboard_utama');
        }

        $user = Auth::user();

        // Menggunakan 'with' agar relasi absensi hari ini ditarik sekaligus secara instan (Anti N+1 Problem)
        $siswas = siswa::where('role', 'murid')
            ->with(['attendances' => function($query) {
                $query->where('tanggal', today()->toDateString());
            }])
            ->get(); 

        return view('dashboard_utama', compact('user', 'siswas'));
    }

    /**
     * Process Login Multi-Aktor (Bisa pakai Username atau NIS)
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $loginInput = $request->input('username');
        $password = $request->input('password');
        $field = 'username';

        $credentials = [
            $field => $loginInput,
            'password' => $password
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if ($user->role == 'murid') {
                return redirect('/')->with('success', 'Halo Siswa, selamat datang di sistem presensi!');
            }

            return redirect('/')->with('success', 'Selamat datang kembali!');
        }

        return redirect('/')->with('error', 'Gagal masuk! ID / Username atau Password salah.');
    }

    /**
     * Proses Logout Aktor
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * CRUD: Menyimpan Data Murid Baru (Akses: Guru)
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'name' => 'required',
            'password' => 'required',
        ]);

        siswa::create([
            'username' => $request->username,
            'name' => $request->name,
            'nomor_wa_ortu' => $request->nomor_wa_ortu,
            'telegram_id_ortu' => $request->telegram_id_ortu,
            'password' => bcrypt($request->password), 
            'role' => 'murid', 
        ]);

        return redirect('/')->with('success', 'Data murid berhasil ditambahkan!');
    }

    /**
     * CRUD: Menghapus Data Murid (Akses: Guru)
     */
    public function destroy($id)
    {
        $siswa = siswa::findOrFail($id);
        $siswa->delete();

        return redirect('/')->with('success', 'Data murid berhasil dihapus!');
    }

    /**
     * FITUR: Kirim Notifikasi Hadir via Telegram Bot (Versi Aman Anti-Crash)
     */
    public function notifHadir($id)
    {
        $siswa = siswa::findOrFail($id);
        $chat_id = $siswa->telegram_id_ortu;

        if (!$chat_id) {
            return redirect('/')->with('error', 'Gagal! Chat ID Telegram Orang Tua murid ini belum diisi.');
        }
        
        $token = env('TELEGRAM_BOT_TOKEN'); 
        $pesan = "Info MTs Mambaul Ulum: Ananda " . $siswa->name . " telah hadir di sekolah dengan presensi fingerprint pada jam " . date('H:i') . " WIB.";

        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['chat_id' => $chat_id, 'text' => $pesan]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
        curl_exec($ch);
        curl_close($ch);

        return redirect('/')->with('success', 'Notifikasi kehadiran via Telegram berhasil diproses!');
    }

    /**
     * FITUR: Kirim Notifikasi Tidak Hadir / WA via Fonnte (VERSI SINGLE LINE ANTI-EROR)
     */
    public function notifWa($id)
    {
        $siswa = siswa::findOrFail($id);
        $target = $siswa->nomor_wa_ortu;

        if (!$target) {
            return redirect('/')->with('error', 'Gagal! Nomor WhatsApp Orang Tua murid ini belum diisi.');
        }

        $token_fonnte = env('FONNTE_TOKEN'); 
        $pesan = "Pemberitahuan MTs Mambaul Ulum: Ananda " . $siswa->name . " sampai saat ini BELUM hadir di kelas tanpa keterangan. Mohon konfirmasinya.";

        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_URL, 'https://api.fonnte.com/send');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, array('target' => $target, 'message' => $pesan));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: " . $token_fonnte));
        
        curl_exec($curl);
        curl_close($curl);
        
        return redirect('/')->with('success', 'Pemberitahuan absensi via WhatsApp berhasil diproses!');
    }

    /**
     * FITUR BARU: Menyimpan Absensi Izin beserta Alasannya ke HeidiSQL
     */
    public function simpanIzin(Request $request)
    {
        $request->validate([
            'siswa_id'   => 'required',
            'keterangan' => 'required|string|max:255',
        ]);

        Attendance::create([
            'siswa_id'   => $request->siswa_id,
            'waktu_scan' => now(),
            'status'     => 'Izin',
            'keterangan' => $request->keterangan,
        ]);

        return redirect('/')->with('success', 'Data izin siswa berhasil disimpan!');
    }
}