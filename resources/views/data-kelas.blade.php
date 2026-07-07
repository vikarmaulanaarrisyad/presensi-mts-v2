<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kelas - MTs Mambaul Ulum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        /* AREA KONTEN UTAMA DATA KELAS */
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

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.82rem;
            font-weight: 500;
        }

        .stat-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        /* KARTU MONITORING KELAS */
        .class-box-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
            transition: all 0.25s ease;
            overflow: hidden;
        }

        .class-box-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }

        .progress-bar-custom {
            height: 6px;
            border-radius: 100px;
            background-color: #f1f5f9;
        }

        .font-mono-custom {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.82rem;
        }

        .btn-action-view {
            background-color: #f8fafc;
            color: #334155;
            border: 1px solid #e2e8f0;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .btn-action-view:hover {
            background-color: var(--sidebar-bg);
            color: #ffffff;
            border-color: transparent;
        }

        /* MODAL DESIGN */
        .modal-style-content {
            border-radius: 16px;
            border: none;
            overflow: hidden;
        }

        .modal-style-header {
            background: var(--sidebar-bg);
            color: #ffffff;
            padding: 1.25rem 1.5rem;
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

        @if(session('user_role') === 'admin' || session('user_role') === 'kepsek')
        <a class="nav-link-custom {{ Request::is('siswa/rekap-pdf') || Request::is('admin/rekap-pdf') ? 'active' : '' }}" href="{{ route('siswa.rekap_pdf') }}">
            <i class="fa-solid fa-file-invoice"></i> Rekap Presensi / Laporan
        </a>
        @endif

        <div class="menu-section-title">Konfigurasi</div>
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
                <h2 class="panel-title">Sistem Manajemen Kelas</h2>
                <span class="panel-subtitle">Data Ruang dan Pemetaan Kelas Terintegrasi Cloud</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                @if(session('user_role') === 'kepsek' || session('user_role') === 'admin')
                <button class="btn btn-success fw-bold px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" style="font-size: 0.88rem; background-color: var(--accent-green); border:none;" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
                    <i class="fa-solid fa-plus-circle fs-5"></i> Tambah Kelas
                </button>
                @endif
                
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

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card-white d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-number">{{ isset($kelases) ? count($kelases) : 0 }}</div>
                        <div class="stat-label">Total Ruang Kelas</div>
                    </div>
                    <div class="stat-icon-box bg-success bg-opacity-10 text-success">
                        <i class="fa-school fa-solid"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-white d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-number">94%</div>
                        <div class="stat-label">Rata-rata Presensi</div>
                    </div>
                    <div class="stat-icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-square-poll-vertical"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-white d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-number font-mono-custom text-success" style="font-size:1.1rem; font-weight:700; margin-top:10px; margin-bottom:12px;">CONNECTED</div>
                        <div class="stat-label">Live Cloud Sync</div>
                    </div>
                    <div class="stat-icon-box bg-info bg-opacity-10 text-info">
                        <i class="fa-solid fa-cloud"></i>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="fw-bold text-dark mb-3">Daftar Pemetaan Ruang Aktif</h5>
        <div class="row g-4">
            @forelse($kelases as $kls)
            @php
                $persen = $kls->persentase_hadir ?? 0;
                if ($persen >= 90) {
                    $badgeClass = 'bg-success bg-opacity-10 text-success';
                    $progressClass = 'bg-success';
                } elseif ($persen >= 75) {
                    $badgeClass = 'bg-warning bg-opacity-10 text-warning';
                    $progressClass = 'bg-warning';
                } else {
                    $badgeClass = 'bg-danger bg-opacity-10 text-danger';
                    $progressClass = 'bg-danger';
                }
            @endphp
            <div class="col-md-4">
                <div class="class-box-card">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="fw-bold text-dark mb-0" style="font-size: 1.25rem;">
                                    Kelas {{ $kls->nama_kelas }}
                                </h4>
                                <span class="text-muted font-mono-custom text-uppercase" style="font-size:0.75rem;">
                                    ID: {{ $kls->id_ruang }}
                                </span>
                            </div>
                            <span class="badge {{ $badgeClass }} rounded-pill px-2 py-1 font-mono-custom fw-bold" style="font-size:0.75rem;">
                                {{ $persen }}% Hadir
                            </span>
                        </div>
                        <hr class="text-muted my-3 opacity-25">
                        <div class="small mb-3">
                            <div class="d-flex justify-content-between text-muted mb-1">
                                <span>Kapasitas Terisi:</span>
                                <span class="fw-bold text-dark">{{ $kls->jumlah_siswa }} Siswa Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center text-muted py-5">
                    <i class="fa-solid fa-school fa-2x mb-2"></i>
                    <p>Belum ada data kelas yang terdaftar.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- MODAL TAMBAH KELAS -->
    <div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-labelledby="modalTambahKelasLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-style-content">
                <div class="modal-header modal-style-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="modalTambahKelasLabel" style="font-size:1.05rem;"><i class="fa-solid fa-plus-circle me-2"></i>Tambah Kelas Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('kelas.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nama Kelas</label>
                            <input type="text" name="nama_kelas" class="form-control py-2.5" placeholder="Contoh: VII - A" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Wali Kelas</label>
                            <input type="text" name="wali_kelas" class="form-control py-2.5" placeholder="Nama lengkap wali kelas" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">ID Ruang</label>
                                <input type="text" name="id_ruang" class="form-control py-2.5" placeholder="Contoh: R-01" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Kapasitas</label>
                                <input type="number" name="kapasitas" class="form-control py-2.5" placeholder="Maks siswa" value="32" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2.5 text-white rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-save"></i> Simpan Data Kelas
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (required for modals) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Display Validation Errors or Success Messages -->
    @if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            alert("{{ session('success') }}");
        });
    </script>
    @endif
    @if($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            alert("{{ $errors->first() }}");
        });
    </script>
    @endif

</body>
</html>