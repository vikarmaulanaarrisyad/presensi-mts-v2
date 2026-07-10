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
// Support untuk URL tanpa /fingerprint
// Route::middleware('throttle:60,1')->group(function () {
//     Route::post('/presensi', [AttendanceController::class, 'kirimPresensi']);
//     Route::get('/cek-status-alat', [AttendanceController::class, 'cekStatusServer']);
//     Route::post('/konfirmasi-enroll', [AttendanceController::class, 'konfirmasiEnrollServer']);
//     Route::post('/konfirmasi-hapus', [AttendanceController::class, 'konfirmasiHapusServer']);
// });

// Support untuk URL yang menggunakan /fingerprint
Route::middleware('throttle:60,1')->prefix('fingerprint')->group(function () {
    Route::post('/presensi', [AttendanceController::class, 'kirimPresensi']);
    Route::get('/cek-status-alat', [AttendanceController::class, 'cekStatusServer']);
    Route::post('/konfirmasi-enroll', [AttendanceController::class, 'konfirmasiEnrollServer']);
    Route::post('/konfirmasi-hapus', [AttendanceController::class, 'konfirmasiHapusServer']);
    Route::post('/konfirmasi-reset-semua', [AttendanceController::class, 'konfirmasiResetSemuaJari']);
});