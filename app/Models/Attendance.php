<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model {
    // Di sini student_id sudah kita ganti menjadi siswa_id sesuai database HeidiSQL-mu
    protected $fillable = ['siswa_id', 'waktu_scan', 'status', 'keterangan', 'sumber'];

    // Ini fungsi penghubung ke data siswa
    public function student() {
        return $this->belongsTo(Student::class, 'siswa_id');
    }
}