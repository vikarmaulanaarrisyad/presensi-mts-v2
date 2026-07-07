<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';

    protected $fillable = [
        'nama_alat',
        'ip_address', 
        'status',
        'last_ping',
    ];

    protected $casts = [
        'last_ping' => 'datetime',
    ];
}