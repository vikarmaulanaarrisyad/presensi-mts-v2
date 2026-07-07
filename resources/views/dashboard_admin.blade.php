<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Presensi MTs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #2c3e50; color: white; padding-top: 20px; }
        .sidebar a { color: #ecf0f1; text-decoration: none; display: block; padding: 12px 20px; }
        .sidebar a:hover { background-color: #34495e; color: #3498db; }
        .sidebar a.active { background-color: #34495e; color: #3498db; border-left: 4px solid #3498db; }
        .main-content { padding: 30px; }
        .card-custom { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR SUDAH DITAMBAH MENU BARU -->
        <div class="col-md-3 col-lg-2 sidebar px-0">
            <h5 class="text-center mb-4 fw-bold text-info">MENU ADMIN</h5>
            <hr class="bg-secondary">
            <!-- <a href="{{ url('/dashboard-admin') }}" class="active fw-bold">ðŸ   Dashboard Admin</a> -->
            <!-- <a href="{{ route('peta.penggunaan') }}">ðŸ—ºï¸  Peta Penggunaan</a> -->
            <a href="{{ url('/data-siswa') }}">👨‍🎓 Kelola Data Siswa</a>
            <a href="{{ url('/data-guru') }}">👨‍🏫 Kelola Data Guru</a>
            <a href="{{ url('/data-kelas') }}">ðŸ « Kelola Data Kelas</a>
            <a href="{{ url('/data-alat') }}">ðŸ– ï¸  Kelola Alat/Perangkat</a> <!-- MENU BARU -->
            <a href="{{ url('/siswa') }}">ðŸ“Š Monitoring Presensi</a>
            <hr class="bg-secondary">
            <a href="{{ url('/logout') }}" class="text-danger fw-bold" onclick="return confirm('Apakah Anda yakin ingin keluar?')">ðŸšª Keluar / Logout</a>
        </div>

        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Sistem Kontrol Utama Admin</h2>
                <span class="badge bg-success p-2 fs-6">Mode: Administrator</span>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card card-custom bg-white p-4 mb-4">
                <h4 class="text-secondary">Selamat Datang Kembali, <span class="text-primary fw-bold">{{ session('user_name') }}</span>!</h4>
                <p class="text-muted mb-0">Melalui halaman ini, Anda memiliki hak penuh untuk memantau, mengedit data siswa, data kelas, data alat, serta mengelola sinkronisasi database cloud pada sistem presensi fingerprint MTs Mambaul Ulum Kota Tegal.</p>
            </div>

            <!-- 4 KOTAK MENU SUDAH LENGKAP -->
            <div class="row g-3">
                <!-- Data Siswa -->
                <div class="col-md-6 col-lg-3">
                    <div class="card card-custom bg-primary text-white p-3 h-100">
                        <h5>Data Siswa</h5>
                        <p class="small">Kelola biodata, NIS, dan enroll fingerprint siswa.</p>
                        @php $f = $premiumFeatures['data-siswa'] ?? null; @endphp
                        @if($f && $f->is_active && !$f->is_unlocked)
                            <a href="{{ url('/data-siswa') }}" class="btn btn-sm btn-light fw-bold text-danger mt-auto"><i class="fa-solid fa-lock"></i> Terkunci (Premium)</a>
                        @else
                            <a href="{{ url('/data-siswa') }}" class="btn btn-sm btn-light fw-bold text-primary mt-auto">Buka Data â†’</a>
                        @endif
                    </div>
                </div>

                <!-- Data Kelas -->
                <div class="col-md-6 col-lg-3">
                    <div class="card card-custom bg-warning text-dark p-3 h-100">
                        <h5>Data Kelas</h5>
                        <p class="small">Kelola pembagian kelas dan pemetaan ruangan.</p>
                        @php $f = $premiumFeatures['data-kelas'] ?? null; @endphp
                        @if($f && $f->is_active && !$f->is_unlocked)
                            <a href="{{ url('/data-kelas') }}" class="btn btn-sm btn-dark fw-bold text-danger mt-auto"><i class="fa-solid fa-lock"></i> Terkunci (Premium)</a>
                        @else
                            <a href="{{ url('/data-kelas') }}" class="btn btn-sm btn-dark fw-bold text-warning mt-auto">Buka Data â†’</a>
                        @endif
                    </div>
                </div>

                <!-- Data Alat -->
                <div class="col-md-6 col-lg-3">
                    <div class="card card-custom bg-success text-white p-3 h-100">
                        <h5>Alat / Perangkat</h5>
                        <p class="small">Pengaturan IP dan Status Alat Scanner Fingerprint.</p>
                        @php $f = $premiumFeatures['data-alat'] ?? null; @endphp
                        @if($f && $f->is_active && !$f->is_unlocked)
                            <a href="{{ url('/data-alat') }}" class="btn btn-sm btn-light fw-bold text-danger mt-auto"><i class="fa-solid fa-lock"></i> Terkunci (Premium)</a>
                        @else
                            <a href="{{ url('/data-alat') }}" class="btn btn-sm btn-light fw-bold text-success mt-auto">Buka Data â†’</a>
                        @endif
                    </div>
                </div>

                <!-- Monitoring -->
                <div class="col-md-6 col-lg-3">
                    <div class="card card-custom bg-info text-white p-3 h-100">
                        <h5>Monitoring Presensi</h5>
                        <p class="small">Pantau data kehadiran absensi real-time hari ini.</p>
                        @php $f = $premiumFeatures['siswa'] ?? null; @endphp
                        @if($f && $f->is_active && !$f->is_unlocked)
                            <a href="{{ url('/siswa') }}" class="btn btn-sm btn-light fw-bold text-danger mt-auto"><i class="fa-solid fa-lock"></i> Terkunci (Premium)</a>
                        @else
                            <a href="{{ url('/siswa') }}" class="btn btn-sm btn-light fw-bold text-info mt-auto">Buka Data â†’</a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

