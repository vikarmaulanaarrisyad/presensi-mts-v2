<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    private function cekAdmin()
    {
        if (!session('logged_in')) return redirect('/');
        if (!in_array(session('user_role'), ['admin', 'superadmin'])) {
            return redirect('/')->with('error', 'Akses ditolak! Khusus Administrator.');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->cekAdmin()) return $redirect;

        $gurus = DB::table('users')->whereIn('role', ['guru', 'kepsek'])->orderBy('name')->get();
        return view('data-guru', compact('gurus'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->cekAdmin()) return $redirect;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:guru,kepsek',
            'jabatan' => 'nullable|string|max:255'
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'jabatan' => $request->jabatan,
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Data Guru berhasil ditambahkan! Password default: 12345678');
    }

    public function update(Request $request, $id)
    {
        if ($redirect = $this->cekAdmin()) return $redirect;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role' => 'required|in:guru,kepsek',
            'jabatan' => 'nullable|string|max:255'
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'jabatan' => $request->jabatan,
            'updated_at' => now()
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        DB::table('users')->where('id', $id)->update($updateData);

        return redirect()->back()->with('success', 'Data Guru berhasil diperbarui!');
    }

    public function delete($id)
    {
        if ($redirect = $this->cekAdmin()) return $redirect;

        DB::table('users')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Data Guru berhasil dihapus!');
    }
}
