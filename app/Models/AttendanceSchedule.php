<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_masuk',
        'end_masuk',
        'batas_terlambat',
        'start_pulang',
        'end_pulang',
    ];
}
