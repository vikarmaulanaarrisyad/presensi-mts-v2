<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Guru - MTs Mambaul Ulum</title>
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
                <h2 class="panel-title">Kelola Data Guru</h2>
                <span class="panel-subtitle">Manajemen Akun dan Otorisasi Pengajar di Portal Presensi</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                @if(session('user_role') === 'admin' || session('user_role') === 'superadmin')
                <button class="btn btn-success fw-bold px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" style="font-size: 0.88rem; background-color: var(--accent-green); border:none;" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fa-solid fa-user-plus fs-5"></i> Tambah Akun Baru
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

        <div class="table-container-card">
            <div class="table-responsive">
                <table class="table custom-table table-hover align-middle mb-0">
                    <thead style="background-color: #f8fafc;">
                        <tr>
                            <th class="text-secondary fw-bold text-uppercase" style="font-size:0.75rem; border-bottom: 2px solid #e2e8f0;">No</th>
                            <th class="text-secondary fw-bold text-uppercase" style="font-size:0.75rem; border-bottom: 2px solid #e2e8f0;">Nama Lengkap</th>
                            <th class="text-secondary fw-bold text-uppercase" style="font-size:0.75rem; border-bottom: 2px solid #e2e8f0;">Email / Username</th>
                            <th class="text-secondary fw-bold text-uppercase" style="font-size:0.75rem; border-bottom: 2px solid #e2e8f0;">Role Jabatan</th>
                            <th class="text-secondary fw-bold text-uppercase text-end" style="font-size:0.75rem; border-bottom: 2px solid #e2e8f0;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gurus as $index => $guru)
                        <tr>
                            <td class="font-mono-custom text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $guru->name }}</div>
                            </td>
                            <td class="text-muted font-mono-custom" style="font-size: 0.85rem;">{{ $guru->email }}</td>
                            <td>
                                @if($guru->role == 'kepsek')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 font-mono-custom fw-bold" style="font-size:0.75rem;"><i class="fa-solid fa-crown me-1"></i> Kepala Sekolah</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 font-mono-custom fw-bold" style="font-size:0.75rem;"><i class="fa-solid fa-chalkboard-user me-1"></i> Guru</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $guru->id }}">
                                    <i class="fa-solid fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold ms-1" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $guru->id }}">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="modalEdit{{ $guru->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content modal-style-content">
                                    <div class="modal-header modal-style-header bg-primary text-white">
                                        <h5 class="modal-title fw-bold" style="font-size:1.05rem;"><i class="fa-solid fa-edit me-2"></i>Edit Data Guru</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <form action="{{ route('guru.update', $guru->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                                                <input type="text" name="name" class="form-control py-2.5" value="{{ $guru->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Email / Username</label>
                                                <input type="email" name="email" class="form-control py-2.5" value="{{ $guru->email }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Role Jabatan</label>
                                                <select name="role" class="form-select py-2.5" required>
                                                    <option value="guru" {{ $guru->role == 'guru' ? 'selected' : '' }}>Guru</option>
                                                    <option value="kepsek" {{ $guru->role == 'kepsek' ? 'selected' : '' }}>Kepala Sekolah</option>
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Ganti Password (Opsional)</label>
                                                <input type="password" name="password" class="form-control py-2.5" placeholder="Kosongkan jika tidak ingin diubah">
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2.5 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Hapus -->
                        <div class="modal fade" id="modalHapus{{ $guru->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content modal-style-content" style="text-align: center;">
                                    <div class="modal-body p-4">
                                        <div class="mb-3 text-danger">
                                            <i class="fa-solid fa-circle-exclamation fa-3x"></i>
                                        </div>
                                        <h5 class="fw-bold mb-3">Hapus Akun?</h5>
                                        <p class="text-muted small mb-4">Apakah Anda yakin ingin menghapus akun <strong>{{ $guru->name }}</strong>? Data tidak bisa dikembalikan.</p>
                                        <form action="{{ route('guru.delete', $guru->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-light w-50 fw-bold rounded-pill" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger w-50 fw-bold rounded-pill">Ya, Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fa-solid fa-users-slash fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0 fw-bold">Belum Ada Data Guru</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-style-content">
                <div class="modal-header modal-style-header bg-success text-white">
                    <h5 class="modal-title fw-bold" style="font-size:1.05rem;"><i class="fa-solid fa-user-plus me-2"></i>Tambah Akun Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-3 small fw-bold mb-4">
                        <i class="fa-solid fa-info-circle me-1"></i> Password default akun baru adalah: <strong>12345678</strong>
                    </div>
                    <form action="{{ route('guru.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control py-2.5" placeholder="Nama Guru / Kepsek" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Email / Username</label>
                            <input type="email" name="email" class="form-control py-2.5" placeholder="email@sekolah.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Role Jabatan</label>
                            <select name="role" class="form-select py-2.5" required>
                                <option value="guru" selected>Guru</option>
                                <option value="kepsek">Kepala Sekolah</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2.5 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2"><i class="fa-solid fa-save"></i> Simpan Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
