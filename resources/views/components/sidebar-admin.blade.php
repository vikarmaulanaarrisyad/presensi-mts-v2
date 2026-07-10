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
        <a class="nav-link-custom {{ Request::is('pengaturan-jadwal*') ? 'active' : '' }}" href="{{ route('attendance.schedule') }}">
            <i class="fa-solid fa-clock"></i> Pengaturan Jadwal
        </a>
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