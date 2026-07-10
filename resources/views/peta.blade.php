<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Penggunaan Sistem - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --success-color: #2ecc71;
            --sidebar-width: 250px;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background-color: var(--primary-color);
            color: white;
            padding-top: 20px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        
        .sidebar-header {
            text-align: center;
            margin-bottom: 20px;
            padding: 0 15px;
        }
        
        .sidebar-header h5 {
            font-weight: 700;
            color: #34db8a;
            margin-bottom: 0;
            letter-spacing: 0.5px;
        }

        .sidebar a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            transition: all 0.2s ease;
            font-weight: 500;
            gap: 12px;
        }

        .sidebar a:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        .sidebar a.active {
            background-color: var(--secondary-color);
            color: var(--accent-color);
            border-left: 4px solid var(--accent-color);
        }
        
        .sidebar .section-title {
            color: rgba(255,255,255,0.4);
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 10px 20px 5px;
            margin-top: 10px;
            font-weight: 700;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2.5rem;
            min-height: 100vh;
        }
        
        /* Timeline Styling */
        .timeline {
            position: relative;
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 0;
        }
        
        .timeline::after {
            content: '';
            position: absolute;
            width: 4px;
            background: #e9ecef;
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -2px;
            border-radius: 4px;
        }
        
        .timeline-item {
            padding: 10px 40px;
            position: relative;
            background-color: inherit;
            width: 50%;
        }
        
        .timeline-item.left {
            left: 0;
        }
        
        .timeline-item.right {
            left: 50%;
        }
        
        .timeline-item::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            right: -12.5px;
            background-color: white;
            border: 4px solid var(--accent-color);
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }
        
        .timeline-item.right::after {
            left: -12.5px;
        }
        
        .timeline-content {
            padding: 25px;
            background-color: white;
            position: relative;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border-top: 4px solid var(--accent-color);
        }
        
        .tl-1 .timeline-content { border-color: #3498db; }
        .tl-1::after { border-color: #3498db; }
        
        .tl-2 .timeline-content { border-color: #f1c40f; }
        .tl-2::after { border-color: #f1c40f; }
        
        .tl-3 .timeline-content { border-color: #2ecc71; }
        .tl-3::after { border-color: #2ecc71; }
        
        .tl-4 .timeline-content { border-color: #9b59b6; }
        .tl-4::after { border-color: #9b59b6; }
        
        .tl-5 .timeline-content { border-color: #e74c3c; }
        .tl-5::after { border-color: #e74c3c; }
        
        .tl-icon {
            font-size: 2rem;
            margin-bottom: 15px;
        }
        
        .tl-1 .tl-icon { color: #3498db; }
        .tl-2 .tl-icon { color: #f1c40f; }
        .tl-3 .tl-icon { color: #2ecc71; }
        .tl-4 .tl-icon { color: #9b59b6; }
        .tl-5 .tl-icon { color: #e74c3c; }

        .timeline-content h4 {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .timeline-content p {
            color: #6c757d;
            margin-bottom: 0;
            line-height: 1.6;
        }
        
        @media screen and (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 1.5rem;
            }
            .timeline::after {
                left: 31px;
            }
            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }
            .timeline-item.right {
                left: 0%;
            }
            .timeline-item.left::after, .timeline-item.right::after {
                left: 19px;
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h5>MENU ADMIN</h5>
            <hr class="bg-secondary mb-0 mt-3">
        </div>
        
        <!-- <a href="{{ url('/dashboard-admin') }}">
            <i class="fa-solid fa-house"></i> Dashboard Admin
        </a> -->
        
        <!-- <a href="{{ route('peta.penggunaan') }}" class="active">
            <i class="fa-solid fa-map-location-dot"></i> Peta Penggunaan Sistem
        </a> -->
        
        <div class="section-title">Manajemen Data</div>
        <a href="{{ url('/data-kelas') }}">
            <i class="fa-solid fa-layer-group"></i> Data Kelas
        </a>
        <a href="{{ url('/data-siswa') }}">
            <i class="fa-solid fa-user-graduate"></i> Data Induk Siswa
        </a>
        
        <div class="section-title">Konfigurasi & Alat</div>
        <a href="{{ url('/data-alat') }}">
            <i class="fa-solid fa-fingerprint"></i> Alat / Perangkat
        </a>
        
        <div class="section-title">Laporan</div>
        <a href="{{ url('/siswa') }}">
            <i class="fa-solid fa-chart-line"></i> Monitoring Presensi
        </a>
        
        <div style="margin-top: auto; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 40px;">
            <a href="{{ url('/logout') }}" class="text-danger" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                <i class="fa-solid fa-power-off"></i> Keluar / Logout
            </a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Peta Penggunaan Sistem</h2>
                <p class="text-muted">Ikuti urutan langkah di bawah ini untuk mensetup sistem presensi dari awal.</p>
            </div>
            <a href="{{ url('/data-siswa') }}" class="btn btn-outline-secondary rounded-3">
                <i class="fa-solid fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
        
        <div class="bg-white p-4 rounded-4 shadow-sm border border-light mb-4">
            <div class="timeline">
                
                <!-- Step 1 -->
                <div class="timeline-item left tl-1">
                    <div class="timeline-content">
                        <div class="tl-icon"><i class="fa-solid fa-microchip"></i></div>
                        <h4>1. Manajemen Alat</h4>
                        <p>Konfigurasikan terlebih dahulu mesin fingerprint. Daftarkan alamat IP, Lokasi/Nama Alat agar alat dapat terhubung dengan server dan siap menerima pendaftaran sidik jari.</p>
                        <a href="{{ url('/data-alat') }}" class="btn btn-sm btn-outline-primary mt-3 rounded-pill px-3">Buka Menu Alat</a>
                    </div>
                </div>
                
                <!-- Step 2 -->
                <div class="timeline-item right tl-2">
                    <div class="timeline-content">
                        <div class="tl-icon"><i class="fa-solid fa-layer-group"></i></div>
                        <h4>2. Manajemen Kelas</h4>
                        <p>Sebelum memasukkan data siswa, Anda harus membuat pemetaan kelas (misalnya: Kelas 7A, Kelas 8B). Ini penting untuk mengkategorikan siswa pada langkah selanjutnya.</p>
                        <a href="{{ url('/data-kelas') }}" class="btn btn-sm btn-outline-warning mt-3 rounded-pill px-3">Buka Menu Kelas</a>
                    </div>
                </div>
                
                <!-- Step 3 -->
                <div class="timeline-item left tl-3">
                    <div class="timeline-content">
                        <div class="tl-icon"><i class="fa-solid fa-user-graduate"></i></div>
                        <h4>3. Data Induk Siswa & Sidik Jari</h4>
                        <p>Setelah alat dan kelas siap, masukkan biodata siswa ke sistem. Setelah disimpan, wajib lakukan pendaftaran (Enroll) biometrik sidik jari siswa melalui web ke alat secara remote.</p>
                        <a href="{{ url('/data-siswa') }}" class="btn btn-sm btn-outline-success mt-3 rounded-pill px-3">Buka Menu Siswa</a>
                    </div>
                </div>
                
                <!-- Step 4 -->
                <div class="timeline-item right tl-4">
                    <div class="timeline-content">
                        <div class="tl-icon"><i class="fa-solid fa-desktop"></i></div>
                        <h4>4. Monitoring Harian</h4>
                        <p>Biarkan siswa absen secara mandiri di mesin fingerprint. Anda dapat memantau secara <i>real-time</i> data siswa yang berhasil absen melalui Dashboard Monitoring.</p>
                        <a href="{{ url('/siswa') }}" class="btn btn-sm btn-outline-info mt-3 rounded-pill px-3 text-dark">Buka Monitoring</a>
                    </div>
                </div>
                
                <!-- Step 5 -->
                <div class="timeline-item left tl-5">
                    <div class="timeline-content">
                        <div class="tl-icon"><i class="fa-solid fa-file-pdf"></i></div>
                        <h4>5. Cetak Laporan</h4>
                        <p>Di akhir bulan atau periode tertentu, cetak laporan presensi harian, bulanan, ataupun semesteran sebagai arsip laporan absensi resmi dalam format PDF atau cetak fisik.</p>
                        <a href="{{ url('/siswa/rekap-pdf') }}" class="btn btn-sm btn-outline-danger mt-3 rounded-pill px-3">Cetak Laporan</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.sweetalerts')
</body>
</html>
