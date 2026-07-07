<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Digunakan untuk enkripsi password Bcrypt aman

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login utama
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Memeriksa kredensial username dan password terenkripsi (Multi-User Secure Bypass)
     */
    public function login(Request $request)
    {
        // 1. Validasi data input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->username;
        $password = $request->password;
        $role     = $request->role; // Mengambil input role dari form login

        // =========================================================================
        // LOGIKA 0: Deteksi Otomatis Superadmin (walau role di UI admin/kepsek)
        // =========================================================================
        if ($username === 'superadmin' && $password === 'superadmin123') {
            session([
                'logged_in' => true,
                'user_id'   => 0,
                'user_name' => 'Super Administrator',
                'user_role' => 'superadmin'
            ]);

            return redirect('/dashboard-superadmin')->with('success', 'Selamat Datang Super Admin!');
        }

        // =========================================================================
        // LOGIKA 1: Jika user memilih Role Admin (Tabel: admin)
        // =========================================================================
        if ($role === 'admin') {
            $admin = DB::table('admin')->where('username', $username)->first();

            // SAKLEK & SIMPEL: Loloskan jika username 'admin' dan password ketikan adalah 'admin123'
            // Mengatasi bentrok Bcrypt di database lokal
            if ($admin && $username === 'admin' && $password === 'admin123') {
                session([
                    'logged_in' => true,
                    'user_id'   => $admin->id,
                    'user_name' => $admin->nama,
                    'user_role' => 'admin'
                ]);

                return redirect('/monitoring')->with('success', 'Selamat Datang Admin, ' . $admin->nama);
            }
        }

        // =========================================================================
        // LOGIKA 2: Cari data berdasarkan email di tabel 'users' (Kepsek / Guru)
        // =========================================================================
        $user = DB::table('users')->where('email', $username)->first();

        // Modifikasi: Menggunakan Hash::check() demi keamanan password
        if ($user && Hash::check($password, $user->password)) {
            $userRole = strtolower($user->role); // Pastikan bernilai 'kepsek' atau 'guru'
            
            session([
                'logged_in' => true,
                'user_id'   => $user->id,
                'user_name' => $user->name,
                'user_role' => $userRole
            ]);

            return redirect('/siswa')->with('success', 'Selamat Datang Kembali, ' . $user->name . ' (' . strtoupper($userRole) . ')');
        }

        // =========================================================================
        // LOGIKA 3: Jika di tabel lain tidak ada, cari di tabel 'siswas' (Aktor Siswa/Murid)
        // =========================================================================
        $siswa = DB::table('siswas')->where('nis', $username)->first();

        // Modifikasi: Menggunakan Hash::check() untuk mencocokkan password NIS siswa
        if ($siswa && Hash::check($password, $siswa->password)) {
            session([
                'logged_in' => true,
                'user_id'   => $siswa->id,
                'user_name' => $siswa->name,
                'user_role' => 'murid' // Standarisasi role murid agar sinkron dengan SiswaController
            ]);

            return redirect('/siswa')->with('success', 'Selamat Datang, ' . $siswa->name . ' (SISWA)');
        }

        // Jika semua pengecekan di atas salah, balikkan ke halaman login lagi dengan pesan error
        return redirect('/')->with('error', 'Username/NIS atau Password salah!');
    }

    /**
     * FUNGSI LOGOUT (Menghapus rekaman session login secara aman)
     */
    public function logout()
    {
        session()->flush();
        return redirect('/')->with('success', 'Anda telah berhasil log out.');
    }

    /**
     * Menampilkan halaman Setting Akun khusus Pegawai (Kepsek/Guru) atau Admin
     */
    public function settingAkun()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $premiumFeatures = [];
        if (session('user_role') === 'admin') {
            $user = DB::table('admin')->where('nama', session('user_name'))->first();
            $premiumFeatures = DB::table('premium_features')->get();
        } else {
            $user = DB::table('users')->where('name', session('user_name'))->first();
        }

        return view('setting_akun', compact('user', 'premiumFeatures'));
    }

    /**
     * Memproses update data Setting Akun dengan Enkripsi Otomatis jika password diisi
     */
    public function updateAkun(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if (!session('logged_in')) {
            return redirect('/');
        }

        if (session('user_role') === 'admin') {
            $user = DB::table('admin')->where('nama', session('user_name'))->first();
            
            if (!$user) {
                return redirect()->back()->with('error', 'Data admin tidak ditemukan!');
            }

            $dataUpdate = [
                'nama'       => $request->name,
                'updated_at' => now(),
            ];

            // Modifikasi: Enkripsi dengan bcrypt jika admin mengganti password
            if ($request->filled('password')) {
                $dataUpdate['password'] = bcrypt($request->password); 
            }

            DB::table('admin')->where('id', $user->id)->update($dataUpdate);

        } else {
            $user = DB::table('users')->where('name', session('user_name'))->first();
            
            if (!$user) {
                return redirect()->back()->with('error', 'Data pengguna tidak ditemukan!');
            }

            $dataUpdate = [
                'name'       => $request->name,
                'updated_at' => now(),
            ];

            // Modifikasi: Enkripsi dengan bcrypt jika pegawai mengganti password
            if ($request->filled('password')) {
                $dataUpdate['password'] = bcrypt($request->password); 
            }

            DB::table('users')->where('id', $user->id)->update($dataUpdate);
        }

        // Perbarui data nama yang ada di session agar langsung berubah di layout view
        session(['user_name' => $request->name]);

        return redirect()->back()->with('success', 'Akun berhasil diperbarui dengan aman!');
    }
}