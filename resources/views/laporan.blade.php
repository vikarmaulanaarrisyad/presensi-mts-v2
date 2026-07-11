<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Presensi / Laporan - MTs Mambaul Ulum</title>
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

        .stat-card-white {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
        }

        .table-container-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
            overflow: hidden;
            padding: 1.5rem;
        }

        @media (max-width: 991.98px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; height: auto; position: relative; padding: 1rem; }
            .main-content { margin-left: 0; width: 100%; padding: 1.25rem; }
            .avatar-circle { display: none; }
            .sidebar-brand { padding-bottom: 0; margin-bottom: 1rem; border-bottom: none; text-align: left; }
        }
        
        /* Tambahan untuk UI Filter Laporan */
        .form-label {
            font-weight: 600;
            color: #475569;
            font-size: 0.9rem;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-green);
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.15);
        }
        .btn-primary-custom {
            background-color: var(--accent-green);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
        }
        .btn-primary-custom:hover {
            background-color: #146c43;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="avatar-circle d-flex align-items-center justify-content-center overflow-hidden">
                <img src="{{ asset('img/mts.webp') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/a7/Logo_Kementerian_Agama.svg'" alt="Logo MTS Mambaul Ulum" class="w-100 h-100 object-fit-cover">
            </div>
            <div class="brand-title">MTS MAMBAUL ULUM</div>
            <div class="brand-subtitle">KOTA TEGAL</div>
        </div>

        <div class="menu-section-title">Menu Utama</div>
        <a class="nav-link-custom {{ Request::is('siswa') || Request::is('monitoring') ? 'active' : '' }}" href="{{ route('monitoring.index') }}">
            <i class="fa-solid fa-chart-pie"></i> Monitoring Real-Time
        </a>
        
        @if(session('user_role') === 'admin' || session('user_role') === 'kepsek' || session('user_role') === 'superadmin')
        <a class="nav-link-custom {{ Request::is('data-siswa') ? 'active' : '' }}" href="{{ route('data.siswa') }}">
            <i class="fa-solid fa-user-graduate"></i> Data Induk Siswa
        </a>
        <a class="nav-link-custom {{ Request::is('data-guru') ? 'active' : '' }}" href="{{ route('data.guru') }}">
            <i class="fa-solid fa-chalkboard-user"></i> Data Guru
        </a>
        <a class="nav-link-custom {{ Request::is('data-kelas') ? 'active' : '' }}" href="{{ route('data.kelas') }}">
            <i class="fa-solid fa-layer-group"></i> Data Manajemen Kelas
        </a>
        @endif
        
        <a class="nav-link-custom active" href="{{ session('user_role') === 'murid' ? route('siswa.rekap_pdf') : route('admin.rekap.pdf') }}">
            <i class="fa-solid fa-file-invoice"></i> Rekap Presensi / Laporan
        </a>

        @if(session('user_role') === 'admin' || session('user_role') === 'superadmin')
        <div class="menu-section-title">Konfigurasi</div>
        <a class="nav-link-custom {{ Request::is('pengaturan-jadwal') ? 'active' : '' }}" href="{{ route('attendance.schedule') }}">
            <i class="fa-solid fa-clock"></i> Pengaturan Jadwal
        </a>
        <a class="nav-link-custom {{ Request::is('devices*') || Request::is('data-alat*') ? 'active' : '' }}" href="{{ route('devices.index') }}">
            <i class="fa-solid fa-fingerprint"></i> Manajemen Alat
        </a>
        @endif
        
        @if(session('user_role') === 'murid')
        <div class="menu-section-title">Pengaturan</div>
        @endif
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
        
        <div class="top-header-panel mb-4">
            <h2 class="panel-title">Rekap & Laporan Absensi</h2>
            <span class="panel-subtitle">Pilih rentang tanggal dan filter kelas untuk mencetak laporan format PDF tersinkronisasi.</span>
        </div>

<div class="row">
            <div class="col-lg-7">
                <div class="table-container-card">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-filter me-2 text-success"></i> Filter Laporan</h5>
                    
                    <form action="{{ session('user_role') === 'murid' ? route('siswa.rekap.download') : route('admin.rekap.download') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" value="{{ \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        
                        @if(session('user_role') !== 'murid')
                        <div class="mb-4">
                            <label class="form-label">Filter Kelas</label>
                            <select name="kelas" class="form-select">
                                <option value="">-- Semua Kelas --</option>
                                @foreach($daftarKelas as $kls)
                                    <option value="{{ $kls->kelas }}">{{ $kls->kelas }}</option>
                                @endforeach
                            </select>
                            <div class="form-text mt-1" style="font-size:0.8rem;"><i class="fa-solid fa-circle-info text-info"></i> Biarkan "-- Semua Kelas --" untuk rekap seluruh data siswa.</div>
                        </div>
                        @endif
                        
                        <hr class="my-4 border-light">
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary-custom text-white">
                                <i class="fa-solid fa-file-pdf me-2"></i> Cetak & Unduh PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="stat-card-white h-100" style="background-color:#f0fdf4; border-color:#dcfce7;">
                    <div class="d-flex mb-3">
                        <div class="stat-icon-box text-white" style="background-color: var(--accent-green);">
                            <i class="fa-solid fa-lightbulb"></i>
                        </div>
                        <div class="ms-3 pt-1">
                            <h5 class="fw-bold text-dark mb-1">Informasi</h5>
                            <span class="text-secondary" style="font-size:0.85rem;">Cara kerja fitur laporan</span>
                        </div>
                    </div>
                    
                    <p class="text-muted" style="font-size:0.9rem; line-height:1.6;">
                        Sistem ini akan menghitung kehadiran, keterlambatan, izin, sakit, dan alpa secara <strong>otomatis</strong> berdasarkan rentang tanggal yang dipilih.
                        <br><br>
                        Data di dalam PDF sudah dipilah per kelas dan diurutkan berdasarkan nama sehingga sangat memudahkan wali kelas maupun staf administrasi dalam perekapan.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.sweetalerts')
</body>
</html>
