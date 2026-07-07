<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

// ─── Route publik: tes koneksi (tidak perlu token) ───────────────────────────
Route::get('/cek', function() {
    return response()->json([
        'status'    => 'ok',
        'message'   => 'API Nyambung!',
        'timestamp' => now()->toDateTimeString(),
    ]);
})->withoutMiddleware(\App\Http\Middleware\ValidateDeviceToken::class);

// ─── Route yang dilindungi device_token + rate limit ────────────────────────
// Middleware ValidateDeviceToken sudah terpasang global di app.php untuk semua /api/*
// Throttle tambahan sebagai lapisan kedua (backup)
Route::middleware('throttle:60,1')->group(function () {

    // 1. Terima absensi dari alat fingerprint ESP32
    Route::post('/presensi', [AttendanceController::class, 'kirimPresensi']);

    // 2. Polling status perintah dari web setiap 5 detik
    Route::get('/cek-status-alat', [AttendanceController::class, 'cekStatusServer']);

    // 3. Konfirmasi setelah sukses enroll sidik jari di alat
    Route::post('/konfirmasi-enroll', [AttendanceController::class, 'konfirmasiEnrollServer']);

    // 4. Konfirmasi setelah alat berhasil menghapus sidik jari
    Route::post('/konfirmasi-hapus', [AttendanceController::class, 'konfirmasiHapusServer']);

});