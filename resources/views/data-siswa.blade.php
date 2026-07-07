<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - MTs Mambaul Ulum</title>
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

        /* TABEL DATA STYLE */
        .table-container-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
            overflow: hidden;
            padding: 1.5rem;
        }

        .font-mono-custom {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.82rem;
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
        <a class="nav-link-custom {{ Request::is('siswa/rekap-pdf') || Request::is('admin/rekap-pdf') ? 'active' : '' }}" href="{{ route('siswa.rekap_pdf') }}">
            <i class="fa-solid fa-file-invoice"></i> Rekap Presensi / Laporan
        </a>

        <div class="menu-section-title">Konfigurasi</div>
        <a class="nav-link-custom {{ Request::is('devices*') || Request::is('data-alat*') ? 'active' : '' }}" href="{{ route('devices.index') }}">
            <i class="fa-solid fa-fingerprint"></i> Manajemen Alat
        </a>
        <a class="nav-link-custom {{ Request::is('setting-akun') ? 'active' : '' }}" href="{{ route('setting.akun') }}">
            <i class="fa-solid fa-sliders"></i> Pengaturan Akun
        </a>
        <!-- <a class="nav-link-custom {{ Request::is('dashboard-admin') ? 'active' : '' }}" href="{{ url('/dashboard-admin') }}">
            <i class="fa-solid fa-house"></i> Dashboard Admin
        </a> -->
        <!-- <a class="nav-link-custom {{ Request::is('peta-penggunaan') ? 'active' : '' }}" href="{{ route('peta.penggunaan') }}">
            <i class="fa-solid fa-map-location-dot"></i> Peta Penggunaan Sistem
        </a> -->

        <div class="logout-area">
            <a href="{{ route('logout') }}" class="btn-logout-sidebar" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                <i class="fa-solid fa-power-off"></i> Keluar Sistem
            </a>
        </div>
    </div>

    <div class="main-content">
        
        <div class="top-header-panel mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="panel-title">Data Induk Siswa</h2>
                <span class="panel-subtitle">Manajemen Biodata dan Registrasi Biometrik Fingerprint Terintegrasi</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                @if(session('user_role') === 'admin' || session('user_role') === 'kepsek')
                <button class="btn btn-success fw-bold px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" style="font-size: 0.88rem; background-color: var(--accent-green); border:none;" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
                    <i class="fa-solid fa-user-plus fs-5"></i> Tambah Siswa
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

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <strong>Sukses!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> <strong>Peringatan!</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-xmark me-2"></i> <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @php 
            $activeDemos = collect($premiumFeatures ?? [])->filter(function($f) {
                return $f->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($f->demo_expires_at));
            });
        @endphp

        @if($activeDemos->isNotEmpty())
        <div class="alert alert-info shadow-sm rounded-3 mb-4 border-info border-start border-4 bg-white">
            <h6 class="fw-bold text-info mb-2"><i class="fa-solid fa-clock me-2"></i>Status Demo Fitur Premium Aktif</h6>
            <ul class="mb-0 ps-3 small text-dark">
                @foreach($activeDemos as $demo)
                    @php 
                        $expires = \Carbon\Carbon::parse($demo->demo_expires_at);
                        $minutesLeft = ceil(\Carbon\Carbon::now()->floatDiffInMinutes($expires));
                    @endphp
                    <li><strong>{{ $demo->nama_fitur }}:</strong> Tersisa <span class="fw-bold text-danger">{{ $minutesLeft }} menit</span> (berakhir pada {{ $expires->format('H:i') }})</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card-white d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-number">{{ isset($siswas) ? count($siswas) : 0 }}</div>
                        <div class="stat-label">Siswa Terdaftar (VII-A)</div>
                    </div>
                    <div class="stat-icon-box bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-white d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-number">1</div>
                        <div class="stat-label">Kelas Terpetakan</div>
                    </div>
                    <div class="stat-icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-door-open"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-white d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-number font-mono-custom text-success" style="font-size:1.1rem; font-weight:700; margin-top:10px; margin-bottom:12px;">READY</div>
                        <div class="stat-label">Fingerprint Sensor Sync</div>
                    </div>
                    <div class="stat-icon-box bg-info bg-opacity-10 text-info">
                        <i class="fa-solid fa-fingerprint"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container-card">
            <h5 class="fw-bold text-dark mb-4">Daftar Induk Siswa Terintegrasi Cloud</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary" style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">
                        <tr>
                            <th scope="col" class="px-3 py-3" style="width: 80px;">No</th>
                            <th scope="col" class="py-3">Nama Lengkap</th>
                            <th scope="col" class="py-3">NISN</th>
                            <th scope="col" class="py-3">Kelas</th>
                            <th scope="col" class="py-3">ID Fingerprint</th>
                            <th scope="col" class="py-3 text-center" style="width: 220px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.9rem; color: #475569;">
                        @if(isset($siswas) && count($siswas) > 0)
                            @foreach($siswas as $index => $siswa)
                            <tr>
                                <td class="px-3 fw-bold font-mono-custom">{{ $index + 1 }}</td>
                                <td class="fw-semibold text-dark">{{ $siswa->name ?? 'Nama Tidak Ada' }}</td>
                                <td class="font-mono-custom">{{ $siswa->nis ?? '-' }}</td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary border px-2.5 py-1.5 fw-semibold">{{ $siswa->kelas ?? 'VII - A' }}</span></td>
                                <td>
                                    @if($siswa->fingerprint_id)
                                        <span class="text-success font-mono-custom fw-bold">
                                            <i class="fa-solid fa-fingerprint me-1"></i> ID-{{ $siswa->fingerprint_id }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border px-2 py-1 fw-semibold" style="font-size: 0.75rem;">
                                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Belum
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Tombol Edit Siswa -->
                                        @php $fEdit = $premiumFeatures['aksi-edit'] ?? null; @endphp
                                        @if($fEdit && $fEdit->is_active && !$fEdit->is_unlocked && !($fEdit->has_demo && $fEdit->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($fEdit->demo_expires_at))))
                                            <button type="button" class="btn btn-sm btn-outline-primary px-2.5 py-1.5 rounded-3 font-mono-custom" title="Edit Siswa (Premium)" onclick="showPaywall('{{ $fEdit->nama_fitur }}', '{{ $fEdit->harga }}', '{{ $fEdit->has_demo }}', '{{ $fEdit->demo_requested }}', '{{ $fEdit->demo_expires_at }}', '{{ $fEdit->menu_code }}', '{{ $fEdit->max_demo_requests }}', '{{ $fEdit->demo_used_count }}', '{{ !empty($fEdit->payment_requested) ? 1 : 0 }}')"><i class="fa-solid fa-pencil"></i></button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-primary px-2.5 py-1.5 rounded-3 font-mono-custom" data-bs-toggle="modal" data-bs-target="#modalEditSiswa{{ $siswa->id }}" title="Edit Siswa"><i class="fa-solid fa-pencil"></i></button>
                                        @endif
                                        
                                        <!-- TOMBOL INPUT IZIN / SAKIT -->
                                        @php $fIzin = $premiumFeatures['aksi-izin'] ?? null; @endphp
                                        @if($fIzin && $fIzin->is_active && !$fIzin->is_unlocked && !($fIzin->has_demo && $fIzin->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($fIzin->demo_expires_at))))
                                            <button type="button" class="btn btn-sm btn-warning text-white px-2.5 py-1.5 rounded-3" title="Input Keterangan Absen (Premium)" onclick="showPaywall('{{ $fIzin->nama_fitur }}', '{{ $fIzin->harga }}', '{{ $fIzin->has_demo }}', '{{ $fIzin->demo_requested }}', '{{ $fIzin->demo_expires_at }}', '{{ $fIzin->menu_code }}', '{{ $fIzin->max_demo_requests }}', '{{ $fIzin->demo_used_count }}', '{{ !empty($fIzin->payment_requested) ? 1 : 0 }}')"><i class="fa-solid fa-envelope"></i></button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-warning text-white px-2.5 py-1.5 rounded-3" data-bs-toggle="modal" data-bs-target="#modalIzin{{ $siswa->id }}" title="Input Keterangan Absen"><i class="fa-solid fa-envelope"></i></button>
                                        @endif
                                        
                                        <!-- TOMBOL WHATSAPP FONNTE -->
                                        @php $fWA = $premiumFeatures['aksi-whatsapp'] ?? null; @endphp
                                        @if($fWA && $fWA->is_active && !$fWA->is_unlocked && !($fWA->has_demo && $fWA->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($fWA->demo_expires_at))))
                                            <button type="button" class="btn btn-sm btn-outline-success px-2.5 py-1.5 rounded-3" title="Kirim WA Ketidakhadiran (Premium)" onclick="showPaywall('{{ $fWA->nama_fitur }}', '{{ $fWA->harga }}', '{{ $fWA->has_demo }}', '{{ $fWA->demo_requested }}', '{{ $fWA->demo_expires_at }}', '{{ $fWA->menu_code }}', '{{ $fWA->max_demo_requests }}', '{{ $fWA->demo_used_count }}', '{{ !empty($fWA->payment_requested) ? 1 : 0 }}')"><i class="fa-brands fa-whatsapp text-success fw-bold"></i></button>
                                        @else
                                            <a href="{{ route('siswa.notif_wa', $siswa->id) }}" class="btn btn-sm btn-outline-success px-2.5 py-1.5 rounded-3" title="Kirim WA Ketidakhadiran" onclick="return confirm('Kirim notifikasi WhatsApp ketidakhadiran ke orang tua {{ $siswa->name }}?')"><i class="fa-brands fa-whatsapp text-success fw-bold"></i></a>
                                        @endif

                                        @if(session('user_role') === 'admin' || session('user_role') === 'kepsek')
                                            <!-- TOMBOL REKAM/HAPUS JARI -->
                                            @php $fJari = $premiumFeatures['aksi-fingerprint'] ?? null; @endphp
                                            @if($fJari && $fJari->is_active && !$fJari->is_unlocked && !($fJari->has_demo && $fJari->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($fJari->demo_expires_at))))
                                                <button type="button" class="btn btn-sm btn-primary px-2.5 py-1.5 rounded-3" title="Rekam Jari di Alat (Premium)" onclick="showPaywall('{{ $fJari->nama_fitur }}', '{{ $fJari->harga }}', '{{ $fJari->has_demo }}', '{{ $fJari->demo_requested }}', '{{ $fJari->demo_expires_at }}', '{{ $fJari->menu_code }}', '{{ $fJari->max_demo_requests }}', '{{ $fJari->demo_used_count }}', '{{ !empty($fJari->payment_requested) ? 1 : 0 }}')"><i class="fa-solid fa-fingerprint"></i></button>
                                                <!-- We could also replace the reset/hapus buttons but since they don't appear until a fingerprint is registered, and they can't register without unlocking, this is sufficient. But let's handle the visual state exactly as if it was unlocked but block click -->
                                                @if($siswa->fingerprint_id)
                                                    <button type="button" class="btn btn-sm btn-outline-secondary px-2.5 py-1.5 rounded-3" title="Reset Jari (Premium)" onclick="showPaywall('{{ $fJari->nama_fitur }}', '{{ $fJari->harga }}', '{{ $fJari->has_demo }}', '{{ $fJari->demo_requested }}', '{{ $fJari->demo_expires_at }}', '{{ $fJari->menu_code }}', '{{ $fJari->max_demo_requests }}', '{{ $fJari->demo_used_count }}', '{{ !empty($fJari->payment_requested) ? 1 : 0 }}')"><i class="fa-solid fa-rotate-left"></i></button>
                                                    <button type="button" class="btn btn-sm btn-danger px-2.5 py-1.5 rounded-3" title="Hapus Jari dari Alat (Premium)" onclick="showPaywall('{{ $fJari->nama_fitur }}', '{{ $fJari->harga }}', '{{ $fJari->has_demo }}', '{{ $fJari->demo_requested }}', '{{ $fJari->demo_expires_at }}', '{{ $fJari->menu_code }}', '{{ $fJari->max_demo_requests }}', '{{ $fJari->demo_used_count }}', '{{ !empty($fJari->payment_requested) ? 1 : 0 }}')"><i class="fa-solid fa-eraser"></i></button>
                                                @endif
                                            @else
                                                @if(!$siswa->fingerprint_id)
                                                    @if(array_key_exists($siswa->id, $activeEnrolls ?? []))
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-info text-white px-2.5 py-1.5" title="Sedang Menunggu Scan Jari di Alat..." disabled><i class="fa-solid fa-spinner fa-spin"></i></button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger px-2.5 py-1.5" title="Batalkan Pendaftaran" onclick="batalkanRekam({{ $activeEnrolls[$siswa->id] }})"><i class="fa-solid fa-xmark"></i></button>
                                                    </div>
                                                    @else
                                                    <button type="button" class="btn btn-sm btn-primary px-2.5 py-1.5 rounded-3" data-bs-toggle="modal" data-bs-target="#modalRekam{{ $siswa->id }}" title="Rekam Jari di Alat"><i class="fa-solid fa-fingerprint"></i></button>
                                                    @endif
                                                @else
                                                    <form action="{{ route('siswa.reset_jari', $siswa->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mereset/menghapus ikatan sidik jari siswa ini dari database lokal?')" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary px-2.5 py-1.5 rounded-3" title="Reset Jari (Lokal DB)"><i class="fa-solid fa-rotate-left"></i></button>
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-danger px-2.5 py-1.5 rounded-3" data-bs-toggle="modal" data-bs-target="#modalHapusJari{{ $siswa->id }}" title="Hapus Jari dari Alat"><i class="fa-solid fa-eraser"></i></button>
                                                @endif
                                            @endif
                                        @endif
                                        
                                        <!-- Tombol Hapus Siswa -->
                                        @php $fHapus = $premiumFeatures['aksi-hapus'] ?? null; @endphp
                                        @if($fHapus && $fHapus->is_active && !$fHapus->is_unlocked && !($fHapus->has_demo && $fHapus->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($fHapus->demo_expires_at))))
                                            <button type="button" class="btn btn-sm btn-outline-danger px-2.5 py-1.5 rounded-3" title="Hapus Siswa (Premium)" onclick="showPaywall('{{ $fHapus->nama_fitur }}', '{{ $fHapus->harga }}', '{{ $fHapus->has_demo }}', '{{ $fHapus->demo_requested }}', '{{ $fHapus->demo_expires_at }}', '{{ $fHapus->menu_code }}', '{{ $fHapus->max_demo_requests }}', '{{ $fHapus->demo_used_count }}', '{{ !empty($fHapus->payment_requested) ? 1 : 0 }}')"><i class="fa-solid fa-trash-can"></i></button>
                                        @else
                                            <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa bernama {{ $siswa->name }}? Hubungan data fingerprint di HeidiSQL juga akan ikut terhapus.')" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1.5 rounded-3" title="Hapus Siswa"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- MODAL FORM POP-UP INPUT IZIN (DATA ASLI) -->
                            <div class="modal fade" id="modalIzin{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content modal-style-content">
                                        <div class="modal-header modal-style-header bg-warning text-dark">
                                            <h5 class="modal-title fw-bold" style="font-size:1.05rem;"><i class="fa-solid fa-envelope me-2"></i>Input Absen Manual - {{ $siswa->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <form action="{{ url('/simpan-izin') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                                
                                                <div class="mb-3 text-start">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Pilih Status</label>
                                                    <select name="status" class="form-select py-2.5" style="border-radius:8px;" required>
                                                        <option value="Izin">Izin</option>
                                                        <option value="Sakit">Sakit</option>
                                                    </select>
                                                </div>

                                                <div class="mb-4 text-start">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Alasan / Keterangan</label>
                                                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Surat dokter demam, Acara keluarga, dll." style="border-radius:8px;" required></textarea>
                                                </div>

                                                <button type="submit" class="btn btn-warning w-100 fw-bold py-2.5 text-white rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" style="border: none;">
                                                    <i class="fa-solid fa-cloud-arrow-up"></i> Simpan Ke Cloud
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL EDIT SISWA -->
                            <div class="modal fade" id="modalEditSiswa{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content modal-style-content">
                                        <div class="modal-header modal-style-header bg-primary text-white">
                                            <h5 class="modal-title fw-bold" style="font-size:1.05rem;"><i class="fa-solid fa-user-pen me-2"></i>Edit Data Siswa</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4 text-start">
                                            <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
                                                @csrf
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                                                    <input type="text" name="name" class="form-control py-2.5" value="{{ $siswa->name }}" style="border-radius:8px;" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">NISN</label>
                                                    <input type="text" name="nisn" class="form-control py-2.5 font-mono-custom" value="{{ $siswa->nis }}" style="border-radius:8px;" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Pindah Kelas</label>
                                                    <select name="kelas" class="form-select py-2.5" style="border-radius:8px;" required>
                                                        @if(isset($kelases) && count($kelases) > 0)
                                                            @foreach($kelases as $kls)
                                                                <option value="{{ $kls->nama_kelas }}" {{ $siswa->kelas == $kls->nama_kelas ? 'selected' : '' }}>Kelas {{ $kls->nama_kelas }} (Ruang {{ $kls->id_ruang }})</option>
                                                            @endforeach
                                                        @else
                                                            <option value="{{ $siswa->kelas }}">{{ $siswa->kelas }} (Data kelas belum ditambahkan)</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">No. WA Orang Tua</label>
                                                    <input type="text" name="no_wa" class="form-control py-2.5 font-mono-custom" value="{{ $siswa->no_wa }}" style="border-radius:8px;" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100 fw-bold py-2.5 text-white rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" style="border: none;">
                                                    <i class="fa-solid fa-save"></i> Simpan Perubahan
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL REKAM JARI -->
                            <div class="modal fade" id="modalRekam{{ $siswa->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content modal-style-content">
                                        <div class="modal-header modal-style-header bg-primary text-white">
                                            <h5 class="modal-title fw-bold" style="font-size:1.05rem;"><i class="fa-solid fa-fingerprint me-2"></i>Rekam Jari - {{ $siswa->name }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <form id="form-rekam-{{ $siswa->id }}" action="{{ route('siswa.rekam_jari') }}" method="POST" data-name="{{ $siswa->name }}">
                                                @csrf
                                                <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Pilih Alat Fingerprint</label>
                                                    <select name="device_id" class="form-select py-2.5" style="border-radius:8px;" required>
                                                        <option value="">-- Pilih Alat --</option>
                                                        @foreach($devices as $d)
                                                            <option value="{{ $d->id }}">{{ $d->nama_alat }} - {{ $d->ip_address }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-muted d-block mt-2">Pilih alat yang ada di depan Anda sekarang. Setelah menekan tombol rekam, alat akan masuk mode pendaftaran. Tempelkan jari siswa 2 kali ke sensor.</small>
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100 fw-bold py-2.5 text-white rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" style="border: none;">
                                                    <i class="fa-solid fa-paper-plane"></i> Kirim Perintah Rekam
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL HAPUS JARI ALAT -->
                            <div class="modal fade" id="modalHapusJari{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content modal-style-content">
                                        <div class="modal-header modal-style-header bg-danger text-white">
                                            <h5 class="modal-title fw-bold" style="font-size:1.05rem;"><i class="fa-solid fa-eraser me-2"></i>Hapus Jari - {{ $siswa->name }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <form action="{{ route('siswa.hapus_jari_alat') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Pilih Alat Fingerprint</label>
                                                    <select name="device_id" class="form-select py-2.5" style="border-radius:8px;" required>
                                                        <option value="">-- Pilih Alat --</option>
                                                        @foreach($devices as $d)
                                                            <option value="{{ $d->id }}">{{ $d->nama_alat }} - {{ $d->ip_address }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-danger d-block mt-2">Pilih alat tempat sidik jari siswa ini pernah didaftarkan. ID Sidik Jari akan dihapus secara permanen dari alat.</small>
                                                </div>
                                                <button type="submit" class="btn btn-danger w-100 fw-bold py-2.5 text-white rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" style="border: none;">
                                                    <i class="fa-solid fa-trash-can"></i> Hapus dari Alat
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data siswa. Silakan klik "Registrasi Siswa Baru" untuk menambahkan data.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @if(session("user_role") === "admin")
            @php
                $hasLockedCatalog = false;
                foreach($premiumFeatures as $f) {
                    $isDemoActive = false;
                    if ($f->has_demo && $f->demo_expires_at) {
                        if (\Carbon\Carbon::now()->timezone('Asia/Jakarta')->lessThan(\Carbon\Carbon::parse($f->demo_expires_at))) {
                            $isDemoActive = true;
                        }
                    }
                    if($f->is_active && !$f->is_unlocked && !$isDemoActive && in_array($f->menu_code, ["siswa", "siswa/pengajuan-izin"])) {
                        $hasLockedCatalog = true;
                        break;
                    }
                }
            @endphp
            @if($hasLockedCatalog)
            <!-- Katalog Fitur Tambahan -->
            <div class="mt-5 pt-4" style="border-top: 1px dashed #cbd5e1;">
                <h5 class="fw-bold mb-3" style="color: #334155;"><i class="fa-solid fa-store text-warning me-2"></i> Katalog Ekstensi & Fitur Premium</h5>
                <p class="small text-muted mb-4">Fitur opsional untuk meningkatkan fungsionalitas aplikasi dan layanan siswa.</p>
                
                <div class="row g-3">
                    @foreach($premiumFeatures as $f)
                        @php
                            $isDemoActive = false;
                            if ($f->has_demo && $f->demo_expires_at) {
                                if (\Carbon\Carbon::now()->timezone('Asia/Jakarta')->lessThan(\Carbon\Carbon::parse($f->demo_expires_at))) {
                                    $isDemoActive = true;
                                }
                            }
                        @endphp
                        @if($f->is_active && !$f->is_unlocked && !$isDemoActive && in_array($f->menu_code, ["siswa", "siswa/pengajuan-izin"]))
                        <div class="col-md-6">
                            <div class="card h-100" style="border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
                                <div class="card-body p-4 d-flex flex-column">
                                    <h6 class="fw-bold text-dark mb-2">{{ $f->nama_fitur }}</h6>
                                    
                                    <div class="mt-auto pt-3 d-flex justify-content-between align-items-center">
                                        <div class="fw-bold text-success">Rp {{ number_format($f->harga, 0, ",", ".") }}</div>
                                        <button type="button" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3" onclick="showPaywall('{{ $f->nama_fitur }}', '{{ $f->harga }}', '{{ $f->has_demo }}', '{{ $f->demo_requested }}', '{{ $f->demo_expires_at }}', '{{ $f->menu_code }}', '{{ $f->max_demo_requests }}', '{{ $f->demo_used_count }}', '{{ !empty($f->payment_requested) ? 1 : 0 }}')">
                                            <i class="fa-solid fa-lock me-1"></i> Beli
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        @endif

    </div>

    <!-- MODAL REGISTRASI SISWA BARU -->
    <div class="modal fade" id="modalTambahSiswa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-style-content">
                <div class="modal-header modal-style-header">
                    <h5 class="modal-title fw-bold" style="font-size:1.05rem;"><i class="fa-solid fa-user-plus me-2"></i>Registrasi Siswa Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('siswa.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control py-2.5" placeholder="Masukkan nama siswa" style="border-radius:8px;" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">NISN</label>
                            <input type="text" name="nisn" class="form-control py-2.5 font-mono-custom" placeholder="Contoh: 0098451221" style="border-radius:8px;" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Kelas</label>
                            <select name="kelas" class="form-select py-2.5" style="border-radius:8px;" required>
                                <option value="">-- Pilih Kelas --</option>
                                @if(isset($kelases) && count($kelases) > 0)
                                    @foreach($kelases as $kls)
                                        <option value="{{ $kls->nama_kelas }}">Kelas {{ $kls->nama_kelas }} (Ruang {{ $kls->id_ruang }})</option>
                                    @endforeach
                                @else
                                    <option value="VII-A">Kelas VII - A (Default)</option>
                                @endif
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">No. WA Orang Tua (Notifikasi)</label>
                            <input type="text" name="no_wa" class="form-control py-2.5 font-mono-custom" placeholder="Contoh: 081234567890" style="border-radius:8px;" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2.5 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background-color: var(--sidebar-bg); border: none;">
                            <i class="fa-solid fa-floppy-disk"></i> Daftarkan Siswa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto Refresh Status Jari (AJAX) -->
    <script>
        // Event delegation untuk form rekam jari, kebal terhadap auto-refresh tabel
        document.addEventListener('submit', function(event) {
            const form = event.target;
            if (form && form.id && form.id.startsWith('form-rekam-')) {
                event.preventDefault();
                const siswaId = form.id.replace('form-rekam-', '');
                handleRekamJariAction(form, siswaId);
            }
        });

        function handleRekamJariAction(form, siswaId) {
            try {
                const formData = new FormData(form);
                const deviceId = formData.get('device_id');
                const studentName = form.getAttribute('data-name');
                
                // Sembunyikan form secara paksa
                form.style.display = 'none';
                form.classList.add('d-none');
                
                // Buat div loading jika belum ada
                let loadingContainer = document.getElementById('loading-container-' + siswaId);
                if (!loadingContainer) {
                    loadingContainer = document.createElement('div');
                    loadingContainer.id = 'loading-container-' + siswaId;
                    form.parentElement.appendChild(loadingContainer);
                }
                
                loadingContainer.classList.remove('d-none');
                loadingContainer.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fa-solid fa-fingerprint fa-beat-fade fa-4x text-primary mb-4"></i>
                        <h5 class="fw-bold">Menunggu Scan Jari...</h5>
                        <p class="text-primary fw-bold fs-5 mb-2">${studentName}</p>
                        <p class="text-muted small mb-4">Tempelkan jari siswa pada sensor alat ESP32.<br>Jangan tutup jendela ini sampai proses selesai.</p>
                        <button type="button" class="btn btn-outline-danger btn-sm px-4 rounded-3 fw-bold" onclick="batalkanRekam(${deviceId}, ${siswaId}, this)">
                            <i class="fa-solid fa-circle-xmark me-1"></i> Batalkan
                        </button>
                    </div>
                `;

                // Kirim request ke server
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw new Error(err.message || 'Gagal mengirim perintah'); });
                    }
                    return response.json();
                }).then(() => {
                    // Polling status per 2 detik
                    const poll = setInterval(() => {
                        // Cek jika proses dibatalkan secara manual oleh user (div loading disembunyikan)
                        if (loadingContainer.classList.contains('d-none')) {
                            clearInterval(poll);
                            return;
                        }

                        fetch(`/cek-rekam/${siswaId}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.fingerprint_id !== null) {
                                    clearInterval(poll);
                                    loadingContainer.innerHTML = `
                                        <div class="text-center py-5">
                                            <i class="fa-solid fa-circle-check fa-4x text-success mb-4"></i>
                                            <h5 class="fw-bold">Berhasil!</h5>
                                            <p class="text-muted small mb-3">Sidik jari berhasil direkam dan disimpan.</p>
                                            <button type="button" class="btn btn-success px-4 rounded-3" onclick="location.reload()">Selesai</button>
                                        </div>
                                    `;
                                    setTimeout(() => location.reload(), 2000);
                                } else if (data.device_status !== 'enroll' && data.device_status !== 'delete') {
                                    clearInterval(poll);
                                    loadingContainer.innerHTML = `
                                        <div class="text-center py-5">
                                            <i class="fa-solid fa-circle-xmark fa-4x text-danger mb-4"></i>
                                            <h5 class="fw-bold">Gagal atau Timeout</h5>
                                            <p class="text-muted small mb-3">Proses rekam jari dibatalkan oleh alat.</p>
                                            <button type="button" class="btn btn-secondary px-4 rounded-3" onclick="location.reload()">Tutup</button>
                                        </div>
                                    `;
                                }
                            });
                    }, 2000);
                }).catch(err => {
                    loadingContainer.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fa-solid fa-triangle-exclamation fa-4x text-warning mb-4"></i>
                            <h5 class="fw-bold">Tidak Bisa Diproses!</h5>
                            <p class="text-muted small mb-3">${err.message}</p>
                            <button type="button" class="btn btn-secondary px-4 rounded-3" onclick="location.reload()">Tutup</button>
                        </div>
                    `;
                });
            } catch (error) {
                alert('Terjadi kesalahan sistem: ' + error.message);
                console.error(error);
            }
        }

        function batalkanRekam(deviceId, siswaId = null, btnElement = null) {
            if (!confirm('Batalkan proses pendaftaran jari ini?')) return;
            
            // Ubah teks tombol menjadi memproses
            if (btnElement) {
                const originalHtml = btnElement.innerHTML;
                btnElement.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Membatalkan...';
                btnElement.disabled = true;
            }

            fetch(`/devices/${deviceId}/force-scan`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                location.reload();
            }).catch(() => {
                alert('Gagal membatalkan. Silakan refresh halaman (F5) dan batalkan dari menu Manajemen Alat.');
                location.reload();
            });
        }

        setInterval(() => {
            // Jangan lakukan refresh jika ada modal yang sedang terbuka
            if (document.querySelector('.modal.show')) {
                return;
            }

            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    // Cek lagi untuk memastikan tidak ada modal yang dibuka saat fetch berlangsung
                    if (document.querySelector('.modal.show')) {
                        return;
                    }

                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Ambil isi area konten utama (termasuk tabel dan modal di dalamnya)
                    const newTableBody = doc.querySelector('.table-responsive');
                    const oldTableBody = document.querySelector('.table-responsive');
                    
                    if (newTableBody && oldTableBody) {
                        oldTableBody.innerHTML = newTableBody.innerHTML;
                    }
                })
                .catch(err => console.error('Gagal memuat status jari:', err));
        }, 3000); // Update setiap 3 detik
    </script>
<!-- MODAL PREMIUM PAYWALL -->
<div class="modal fade" id="modalPremiumPaywall" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-dark text-white border-0" style="border-radius: 15px 15px 0 0;">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-lock text-warning me-2"></i> Fitur Premium Terkunci</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="fa-solid fa-gem text-primary mb-3" style="font-size: 3rem;"></i>
                <h5 class="fw-bold mb-3">Upgrade untuk Menggunakan: <br><span class="text-danger" id="pw-feature-name">Nama Fitur</span></h5>
                <p class="text-muted small mb-4">Fitur ini merupakan modul prabayar tambahan. Untuk mengaktifkannya tanpa batas, silakan lakukan pembayaran investasi modul.</p>
                
                <div class="bg-light p-3 rounded-3 border mb-3 text-start">
                    <div class="row mb-3 pb-2 border-bottom">
                        <div class="col-5 text-muted small fw-bold">Biaya Aktivasi</div>
                        <div class="col-7 fw-bold text-success fs-5">Rp <span id="pw-price">0</span></div>
                    </div>
                    
                    <div class="text-muted small fw-bold mb-2">Pilih Rekening Tujuan:</div>
                    @if($payments && $payments->count() > 0)
                        @foreach($payments as $p)
                        <div class="card mb-2 border-0 shadow-sm">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ $p->nama_bank }}</div>
                                        <div class="text-primary font-monospace small">{{ $p->no_rekening }}</div>
                                        <div class="text-muted small">A.N: {{ $p->atas_nama }}</div>
                                    </div>
                                    <i class="fa-solid fa-building-columns text-muted opacity-50 fa-2x"></i>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="alert alert-secondary py-2 small m-0">Belum ada informasi rekening.</div>
                    @endif
                </div>
                <button type="button" class="btn btn-secondary w-100 fw-bold rounded-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showPaywall(name, price, has_demo, demo_requested, expires_at, menu_code, max_demo, used_demo, payment_requested) {
    document.getElementById('pw-feature-name').innerText = name;
    document.getElementById('pw-price').innerText = parseInt(price).toLocaleString('id-ID');

    // Remove existing demo alert/button if any
    const existingDemo = document.getElementById('demo-container');
    if (existingDemo) {
        existingDemo.remove();
    }

    const modalBody = document.querySelector('#modalPremiumPaywall .modal-body');
    const demoContainer = document.createElement('div');
    demoContainer.id = 'demo-container';
    demoContainer.className = 'w-100 mb-3 mt-3';

    if (has_demo == '1' || has_demo === true) {
        if (demo_requested == '1' || demo_requested === true) {
            demoContainer.innerHTML = '<div class="alert alert-info py-2 small text-center m-0"><i class="fa-solid fa-hourglass-half me-1"></i> Pengajuan demo sedang diproses oleh Superadmin.</div>';
        } else if (expires_at && new Date() < new Date(expires_at)) {
            demoContainer.innerHTML = '<div class="alert alert-success py-2 small text-center m-0"><i class="fa-solid fa-check-circle me-1"></i> Demo sedang aktif.</div>';
        } else if (parseInt(used_demo) >= parseInt(max_demo)) {
            demoContainer.innerHTML = `<div class="alert alert-danger py-2 small text-center m-0"><i class="fa-solid fa-ban me-1"></i> Batas penggunaan demo (${max_demo} kali) telah habis.</div>`;
        } else {
            // Not requested yet or past but quota available
            const requestUrl = `{{ url('/premium/request-demo') }}/${menu_code}`;
            const sisa = parseInt(max_demo) - parseInt(used_demo);
            
            let pastMsg = '';
            if (expires_at && new Date() > new Date(expires_at)) {
                pastMsg = '<div class="alert alert-warning py-2 small text-center mb-2"><i class="fa-solid fa-clock-rotate-left me-1"></i> Demo sebelumnya telah berakhir.</div>';
            }

            demoContainer.innerHTML = `
                ${pastMsg}
                <form action="${requestUrl}" method="POST" class="w-100 m-0">
                    @csrf
                    <button type="submit" class="btn btn-warning w-100 py-2 fw-bold text-dark" onclick="return confirm('Ajukan demo gratis ke Superadmin? Sisa kuota: ${sisa} kali.')">
                        <i class="fa-solid fa-gift me-2"></i> Ajukan Demo Fitur (Tersisa: ${sisa}x)
                    </button>
                </form>
            `;
        }
    }

    console.log('payment_requested value:', payment_requested, typeof payment_requested);
    if (parseInt(payment_requested) === 1 || payment_requested === true || payment_requested === 'true') {
        demoContainer.innerHTML += `
            <div class="alert alert-info mt-3 py-2 small text-center m-0 fw-bold">
                <i class="fa-solid fa-hourglass-half me-1"></i> Pembayaran sedang diverifikasi Superadmin.
            </div>
        `;
    } else {
        const paymentUrl = `{{ url('/premium/upload-payment') }}/${menu_code}`;
        const paymentFormHtml = `
            <div class="card mt-3 border-0 shadow-sm bg-light">
                <div class="card-body p-3">
                    <form action="${paymentUrl}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2 text-start">
                            <label class="form-label small fw-bold text-muted mb-1">Sudah Transfer? Unggah Bukti:</label>
                            <input type="file" name="bukti_bayar" class="form-control form-control-sm" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm w-100 fw-bold">
                            <i class="fa-solid fa-upload me-1"></i> Konfirmasi Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        `;
        demoContainer.innerHTML += paymentFormHtml;
    }
    
    // Insert before the close button
    const closeBtn = modalBody.querySelector('.btn-secondary');
    modalBody.insertBefore(demoContainer, closeBtn);

    var myModal = new bootstrap.Modal(document.getElementById('modalPremiumPaywall'));
    myModal.show();
}
</script>

</body>
</html>
