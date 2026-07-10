<?php

namespace App\Models;

// Menggunakan Authenticatable agar siswa bisa login
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel yang digunakan di database.
     */
    protected $table = 'siswas';

    /**
     * Kolom yang boleh diisi (Mass Assignment).
     * DISESUAIKAN 100% DENGAN STRUKTUR REAL HEIDISQL (image_682066.png)
     */
    protected $fillable = [
        'nis',              // Kolom nomor 2 di HeidiSQL (Digunakan untuk menampung data NIS/NISN dari form)
        'name',             // Kolom nomor 3 di HeidiSQL
        'password',         // Kolom nomor 4 di HeidiSQL
        'kelas',            // Kolom nomor 8 di HeidiSQL
        'role',             // Kolom nomor 9 di HeidiSQL
        'fingerprint_id',   // Kolom nomor 10 di HeidiSQL
        'no_wa',            // Kolom nomor 11 di HeidiSQL (Untuk fitur WhatsApp Fonnte Orang Tua)
        'pola_sidik_jari',  // Pola Hex Sidik Jari untuk sinkronisasi antar alat
    ];

    /**
     * Kolom yang disembunyikan saat data ditampilkan (keamanan).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Memberitahu Laravel bahwa password disimpan di kolom 'password'.
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * RELASI: Menghubungkan Siswa dengan Data Absensi (HeidiSQL)
     * Menggunakan hasMany karena satu siswa memiliki banyak catatan absensi harian.
     */
    public function attendances()
    {
        // Parameter kedua adalah foreign key di tabel attendances yang mengarah ke tabel siswas
        return $this->hasMany(Attendance::class, 'siswa_id');
    }
}