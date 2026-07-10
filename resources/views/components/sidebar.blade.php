<div class="sidebar">
    <div class="brand-wrapper">
        <img src="{{ asset('img/mts.WEBP') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/a7/Logo_Kementerian_Agama.svg'" class="brand-logo-frame" alt="Logo MTs">
        <div class="brand-text">
            <span class="brand-title">MTs Mambaul Ulum</span>
            <span class="brand-subtitle">KOTA TEGAL</span>
        </div>
    </div>

    <div class="menu-container">
        <div class="menu-group-title">Menu Utama</div>
        <a href="/siswa" class="menu-link {{ request()->is('siswa') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-pie"></i> Monitoring Real-Time
        </a>
        <a href="{{ route('data.siswa') }}" class="menu-link {{ request()->routeIs('data.siswa') ? 'active' : '' }}">
            <i class="fa-solid fa-user-graduate"></i> Data Induk Siswa
        </a>
        <a href="{{ route('data.guru') }}" class="menu-link {{ request()->routeIs('data.guru') ? 'active' : '' }}">
            <i class="fa-solid fa-chalkboard-user"></i> Data Guru
        </a>
        <a href="{{ route('data.kelas') }}" class="menu-link {{ request()->routeIs('data.kelas') ? 'active' : '' }}">
            <i class="fa-solid fa-layer-group"></i> Data Manajemen Kelas
        </a>
        
        {{-- Hak Akses Rekap Laporan untuk Admin dan Kepsek --}}
        @if(session('user_role') === 'admin' || session('user_role') === 'kepsek')
        <a href="{{ route('siswa.rekap_pdf') }}" class="menu-link {{ request()->routeIs('siswa.rekap_pdf') ? 'active' : '' }}">
            <i class="fa-solid fa-file-invoice"></i> Rekap Presensi / Laporan
        </a>
        @endif

        <div class="menu-group-title">Konfigurasi</div>
        <a href="{{ route('attendance.schedule') }}" class="menu-link {{ request()->is('pengaturan-jadwal') ? 'active' : '' }}">
            <i class="fa-solid fa-clock"></i> <span class="menu-text">Pengaturan Jadwal</span>
        </a>
        <a href="{{ route('devices.index') }}" class="menu-link {{ request()->is('devices*') || request()->is('data-alat*') ? 'active' : '' }}">
            <i class="fa-solid fa-fingerprint"></i> <span class="menu-text">Manajemen Alat</span>
        </a>
        <a href="{{ route('setting.akun') }}" class="menu-link {{ request()->is('setting-akun') ? 'active' : '' }}">
            <i class="fa-solid fa-sliders"></i> Pengaturan Akun
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="{{ route('logout') }}" class="menu-link btn-logout" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
            <i class="fa-solid fa-power-off"></i> Keluar Sistem
        </a>
    </div>
</div>
