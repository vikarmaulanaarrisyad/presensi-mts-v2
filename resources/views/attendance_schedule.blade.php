<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Jadwal Absen - MTs Mambaul Ulum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link class="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-bg: #114224; 
            --sidebar-hover: rgba(255, 255, 255, 0.08);
            --sidebar-active: #175830;
            --accent-green: #198754;
            --bg-light: #f8fafc;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: #334155;
            min-height: 100vh;
            display: flex;
            margin: 0;
        }

        /* SIDEBAR STYLE */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            padding: 2rem 1rem 1.5rem 1rem;
        }

        .sidebar-brand {
            text-align: center;
            padding-bottom: 2rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .avatar-circle {
            width: 85px;
            height: 85px;
            background-color: #ffffff;
            border-radius: 50%;
            margin: 0 auto 1rem auto;
        }

        .brand-title {
            color: #ffffff;
            font-weight: 800;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .brand-subtitle {
            color: #2ec4b6;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .menu-section-title {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0.5rem 1rem;
            margin-top: 1rem;
            margin-bottom: 0.25rem;
        }

        .nav-link-custom {
            color: rgba(255, 255, 255, 0.75) !important;
            font-weight: 500;
            font-size: 0.88rem;
            padding: 0.75rem 1rem !important;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            margin-bottom: 0.25rem;
        }

        .nav-link-custom i {
            width: 20px;
            text-align: center;
            font-size: 1.05rem;
        }

        .nav-link-custom:hover {
            color: #ffffff !important;
            background: var(--sidebar-hover);
        }

        .nav-link-custom.active {
            color: #ffffff !important;
            background: var(--sidebar-active);
            font-weight: 600;
            border-left: 4px solid #2ec4b6;
            border-radius: 4px 8px 8px 4px;
            padding-left: calc(1rem - 4px) !important;
        }

        .logout-area {
            margin-top: auto;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-logout-sidebar {
            color: #fca5a5 !important;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .btn-logout-sidebar:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444 !important;
        }

        /* AREA KONTEN UTAMA DATA SISWA */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 2.5rem;
            min-height: 100vh;
        }

        .top-header-panel {
            background: #ffffff;
            border-radius: 16px;
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
            border: 1px solid #e2e8f0;
        }

        .panel-title {
            color: #0f172a;
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 4px;
        }

        .panel-subtitle {
            color: #64748b;
            font-size: 0.88rem;
        }

        @media (max-width: 991.98px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; height: auto; position: relative; padding: 1rem; }
            .main-content { margin-left: 0; width: 100%; padding: 1.25rem; }
            .avatar-circle { display: none; }
            .sidebar-brand { padding-bottom: 0; margin-bottom: 1rem; border-bottom: none; text-align: left; }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="avatar-circle d-flex align-items-center justify-content-center overflow-hidden">
                <img src="{{ asset('img/mts.WEBP') }}" alt="Logo MTS Mambaul Ulum" class="w-100 h-100 object-fit-cover">
            </div>
            <div class="brand-title">MTS MAMBAUL ULUM</div>
            <div class="brand-subtitle">KOTA TEGAL</div>
        </div>

        <div class="menu-section-title">Menu Utama</div>
        <a class="nav-link-custom {{ Request::is('siswa') || Request::is('monitoring') ? 'active' : '' }}" href="{{ route('monitoring.index') }}">
            <i class="fa-solid fa-chart-pie"></i> Monitoring Real-Time
        </a>
        <a class="nav-link-custom {{ Request::is('data-siswa') ? 'active' : '' }}" href="{{ route('data.siswa') }}">
            <i class="fa-solid fa-user-graduate"></i> Data Induk Siswa
        </a>
        <a class="nav-link-custom {{ Request::is('data-guru') ? 'active' : '' }}" href="{{ route('data.guru') }}">
            <i class="fa-solid fa-chalkboard-user"></i> Data Guru
        </a>
        <a class="nav-link-custom {{ Request::is('data-kelas') ? 'active' : '' }}" href="{{ route('data.kelas') }}">
            <i class="fa-solid fa-layer-group"></i> Data Manajemen Kelas
        </a>
        <a class="nav-link-custom {{ Request::is('siswa/rekap-pdf') || Request::is('admin/rekap-pdf') ? 'active' : '' }}" href="{{ route('siswa.rekap_pdf') }}">
            <i class="fa-solid fa-file-invoice"></i> Rekap Presensi / Laporan
        </a>

        <div class="menu-section-title">Konfigurasi</div>
        <a class="nav-link-custom {{ Request::is('pengaturan-jadwal') ? 'active' : '' }}" href="{{ route('attendance.schedule') }}">
            <i class="fa-solid fa-clock"></i> Pengaturan Jadwal
        </a>
        <a class="nav-link-custom {{ Request::is('devices*') || Request::is('data-alat*') ? 'active' : '' }}" href="{{ route('devices.index') }}">
            <i class="fa-solid fa-fingerprint"></i> Manajemen Alat
        </a>
        <a class="nav-link-custom {{ Request::is('setting-akun') ? 'active' : '' }}" href="{{ route('setting.akun') }}">
            <i class="fa-solid fa-sliders"></i> Pengaturan Akun
        </a>

        <div class="logout-area">
            <a href="{{ route('logout') }}" class="btn-logout-sidebar" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                <i class="fa-solid fa-power-off"></i> Keluar Sistem
            </a>
        </div>
    </div>

    <div class="main-content">
        
        <div class="top-header-panel mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="panel-title">Pengaturan Jadwal Absen</h2>
                <span class="panel-subtitle">Konfigurasi waktu masuk, batas terlambat, dan jam pulang siswa</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2 border-start ps-3">
                    <div class="text-end">
                        <div class="fw-bold text-dark lh-sm" style="font-size: 0.88rem;">{{ session('user_name', 'Admin') }}</div>
                        <div class="text-muted font-mono-custom" style="font-size: 0.75rem;">{{ strtoupper(session('user_role', 'ADMIN')) }}</div>
                    </div>
                    <div class="bg-light p-2 rounded-3 text-secondary border">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                </div>
            </div>
        </div>
@if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> Terdapat kesalahan pada isian form:
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom py-3 rounded-top-4">
                        <h5 class="mb-0 fw-bold"><i class="fa-regular fa-clock me-2 text-primary"></i> Form Pengaturan Jam Absen</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('attendance.schedule.update') }}" method="POST">
                            @csrf
                            
                            <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-door-open me-2"></i> Pengaturan Jam Masuk</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary small">Jam Mulai Masuk</label>
                                    <input type="time" name="start_masuk" class="form-control" value="{{ \Carbon\Carbon::parse($schedule->start_masuk)->format('H:i') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary small">Jam Selesai Masuk</label>
                                    <input type="time" name="end_masuk" class="form-control" value="{{ \Carbon\Carbon::parse($schedule->end_masuk)->format('H:i') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-danger small">Batas Terlambat</label>
                                    <input type="time" name="batas_terlambat" class="form-control" value="{{ \Carbon\Carbon::parse($schedule->batas_terlambat)->format('H:i') }}" required>
                                </div>
                            </div>

                            <hr class="text-muted opacity-25">

                            <h6 class="fw-bold text-primary mb-3 mt-4"><i class="fa-solid fa-person-walking-arrow-right me-2"></i> Pengaturan Jam Pulang</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Jam Mulai Pulang</label>
                                    <input type="time" name="start_pulang" class="form-control" value="{{ \Carbon\Carbon::parse($schedule->start_pulang)->format('H:i') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Jam Selesai Pulang</label>
                                    <input type="time" name="end_pulang" class="form-control" value="{{ \Carbon\Carbon::parse($schedule->end_pulang)->format('H:i') }}" required>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm rounded-3">
                                    <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-light">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-circle-info me-2 text-info"></i> Informasi & Petunjuk</h6>
                        <ul class="text-secondary small ps-3 mb-0" style="line-height: 1.8;">
                            <li><strong>Jam Mulai Masuk:</strong> Waktu paling awal alat mengizinkan siswa untuk absen masuk.</li>
                            <li><strong>Jam Selesai Masuk:</strong> Batas akhir waktu absensi masuk diterima.</li>
                            <li><strong>Batas Terlambat:</strong> Jika siswa absen di antara <em>Jam Mulai</em> hingga <em>Batas Terlambat</em>, statusnya <strong>Hadir</strong>. Jika di atas batas tersebut, statusnya <strong>Terlambat</strong>.</li>
                            <li><strong>Jam Mulai Pulang:</strong> Waktu di mana alat akan mengganti fungsinya menjadi penerima absen pulang.</li>
                            <li>Jika siswa melakukan scan di luar jam Masuk & Pulang, alat akan menolak.</li>
                            <li>Siswa tidak bisa melakukan absen masuk dua kali dalam waktu yang sama.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.sweetalerts')
</body>
</html>
