<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperadminController;

// =====================================================
// ROUTE LOGIN & LOGOUT
// =====================================================
Route::get('/',       [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login',  [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// =====================================================
// ROUTE DASHBOARD & MONITORING
// =====================================================
Route::get('/dashboard-superadmin', [SuperadminController::class, 'index'])->name('superadmin.dashboard');
Route::post('/superadmin/premium-features/update', [SuperadminController::class, 'update'])->name('superadmin.features.update');
Route::post('/premium/request-demo/{menu_code}', [SuperadminController::class, 'requestDemo'])->name('premium.request_demo')->where('menu_code', '.*');
Route::post('/superadmin/approve-demo/{menu_code}', [SuperadminController::class, 'approveDemo'])->name('superadmin.approve_demo')->where('menu_code', '.*');
Route::post('/superadmin/reset-demo/{menu_code}', [SuperadminController::class, 'resetDemo'])->name('superadmin.reset_demo')->where('menu_code', '.*');
Route::get('/superadmin/api/pending-demos', [SuperadminController::class, 'apiPendingDemos'])->name('superadmin.api.pending_demos');
Route::delete('/superadmin/payment/{id}', [SuperadminController::class, 'deletePayment'])->name('superadmin.payment.delete');
Route::get('/superadmin/monitoring', [SuperadminController::class, 'monitoring'])->name('superadmin.monitoring');

Route::post('/premium/upload-payment/{menu_code}', [SuperadminController::class, 'uploadPayment'])->name('premium.upload_payment')->where('menu_code', '.*');
Route::post('/superadmin/payment/approve/{id}', [SuperadminController::class, 'approvePayment'])->name('superadmin.payment.approve');
Route::post('/superadmin/payment/reject/{id}', [SuperadminController::class, 'rejectPayment'])->name('superadmin.payment.reject');

Route::get('/dashboard-admin', [SiswaController::class, 'index'])->name('dashboard.admin');

Route::middleware(['premium'])->group(function () {
    Route::get('/peta-penggunaan', [SiswaController::class, 'peta'])->name('peta.penggunaan');
    Route::get('/monitoring',      [SiswaController::class, 'index'])->name('monitoring.index');
    Route::get('/siswa',           [SiswaController::class, 'index']);

    // =====================================================
    // ROUTE DATA SISWA (CRUD)
    // =====================================================
    Route::get('/data-siswa', [SiswaController::class, 'dataSiswa'])->name('data.siswa');
    Route::get('/api/data-siswa/datatable', [SiswaController::class, 'dataSiswaDatatable'])->name('api.data.siswa');
    Route::post('/siswa/store', [SiswaController::class, 'store'])->name('siswa.store');
    Route::post('/siswa/update/{id}', [SiswaController::class, 'update'])->name('siswa.update');

    Route::delete('/siswa/delete/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    Route::post('/siswa/delete/{id}',   [SiswaController::class, 'destroy']); // Fallback buat form lama

    Route::post('/siswa/rekam-jari', [SiswaController::class, 'rekamJari'])->name('siswa.rekam_jari');
    Route::post('/siswa/hapus-jari-alat', [SiswaController::class, 'hapusJariAlat'])->name('siswa.hapus_jari_alat');
    Route::post('/siswa/{id}/reset-jari', [SiswaController::class, 'resetJariLokal'])->name('siswa.reset_jari');
    Route::post('/siswa/{id}/sync-jari', [SiswaController::class, 'syncFingerprint'])->name('siswa.sync_jari');

    // PENGATURAN JADWAL ABSEN
    Route::get('/pengaturan-jadwal', [\App\Http\Controllers\AttendanceScheduleController::class, 'index'])->name('attendance.schedule');
    Route::post('/pengaturan-jadwal/update', [\App\Http\Controllers\AttendanceScheduleController::class, 'update'])->name('attendance.schedule.update');

    Route::get('/data-kelas', [SiswaController::class, 'dataKelas'])->name('data.kelas');
    Route::post('/kelas/store', [SiswaController::class, 'storeKelas'])->name('kelas.store');
    Route::post('/kelas/update/{id}', [SiswaController::class, 'updateKelas'])->name('kelas.update');
    Route::delete('/kelas/delete/{id}', [SiswaController::class, 'deleteKelas'])->name('kelas.delete');
    Route::post('/kelas/delete/{id}', [SiswaController::class, 'deleteKelas']); // Fallback

    // ROUTE DATA GURU
    Route::get('/data-guru', [GuruController::class, 'index'])->name('data.guru');
    Route::post('/guru/store', [GuruController::class, 'store'])->name('guru.store');
    Route::post('/guru/update/{id}', [GuruController::class, 'update'])->name('guru.update');
    Route::delete('/guru/delete/{id}', [GuruController::class, 'delete'])->name('guru.delete');
    Route::post('/guru/delete/{id}', [GuruController::class, 'delete']); // Fallback for old forms

    // Device Routes
    Route::get('/data-alat', [DeviceController::class, 'index'])->name('data.alat');
});

// =====================================================
// ROUTE NOTIFIKASI WHATSAPP FONNTE
// =====================================================
Route::get('/siswa/notif-wa/{id}', [SiswaController::class, 'notif_wa'])->name('siswa.notif_wa');

// =====================================================
// ROUTE IZIN MANUAL & REKAP PDF
// =====================================================
Route::post('/simpan-izin', [SiswaController::class, 'simpanIzin'])->name('attendance.storeManual');
Route::post('/siswa/pengajuan-izin', [SiswaController::class, 'pengajuanIzinSiswa'])->name('siswa.pengajuan_izin');

Route::get('/admin/rekap-pdf', [SiswaController::class, 'laporanIndex'])->name('admin.rekap.pdf');
Route::get('/siswa/rekap-pdf', [SiswaController::class, 'laporanIndex'])->name('siswa.rekap_pdf');
Route::post('/admin/rekap-pdf/download', [SiswaController::class, 'rekapPdf'])->name('admin.rekap.download');
Route::post('/siswa/rekap-pdf/download', [SiswaController::class, 'rekapPdf'])->name('siswa.rekap.download');

// =====================================================
// ROUTE SETTING AKUN
// =====================================================
Route::get('/setting-akun',         [AuthController::class, 'settingAkun'])->name('setting.akun');
Route::post('/setting-akun/update', [AuthController::class, 'updateAkun'])->name('setting.akun.update');

// =====================================================
// ROUTE UJI COBA INTEGRASI FIREBASE CLOUD
// =====================================================
Route::get('/tes-firebase', [SiswaController::class, 'tesFirebase']);

// =====================================================
// === ROUTE MANAJEMEN ALAT FINGERPRINT ===
// =====================================================
Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
Route::delete('/devices/{id}', [DeviceController::class, 'destroy'])->name('devices.destroy');
Route::get('/devices/{id}/ping', [DeviceController::class, 'ping'])->name('devices.ping');
Route::post('/devices/{id}/force-scan', [DeviceController::class, 'forceScanMode'])->name('devices.force_scan');
Route::post('/devices/{id}/lock', [DeviceController::class, 'lockDevice'])->name('devices.lock');

// === ROUTE BARU MENU SIDEBAR === [TAMBAHAN]
Route::get('/data-alat', [DeviceController::class, 'index'])->name('alat.index');

// =========================================================================
// FITUR IoT INTEGRASI JOKI ALAT: PEMICU ENROLL & DELETE JARI KE DATABASE
// =========================================================================
