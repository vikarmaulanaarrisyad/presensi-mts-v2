<!DOCTYPE html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Guru - MTs Mambaul Ulum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --secondary: #6366f1;
            --dark: #0f172a;
            --gray-light: #f8fafc;
            --text-main: #334155;
            --text-muted: #64748b;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: var(--text-main);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            padding-bottom: 50px;
        }

        /* Subtle background decoration */
        body::before {
            content: '';
            position: absolute;
            top: -200px;
            right: -200px;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, rgba(255,255,255,0) 70%);
            z-index: -1;
        }
        body::after {
            content: '';
            position: absolute;
            top: 100px;
            left: -300px;
            width: 800px;
            height: 800px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, rgba(255,255,255,0) 70%);
            z-index: -1;
        }

        /* Topbar Floating Glass */
        .glass-navbar {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        }

        .navbar-brand-text {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
            background: linear-gradient(90deg, var(--dark), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0;
        }

        /* Profile Banner (Gradient) */
        .profile-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 24px;
            padding: 40px 40px;
            margin-top: 40px;
            box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.2);
            color: white;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 30px;
        }
        
        .profile-banner::after {
            content: '';
            position: absolute;
            right: 0;
            bottom: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(30%, 30%);
        }

        .avatar-glow {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #fff;
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.3);
            backdrop-filter: blur(10px);
            z-index: 2;
        }

        .profile-info {
            z-index: 2;
            flex-grow: 1;
        }

        .profile-name {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .badge-info {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 500;
            backdrop-filter: blur(4px);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .sensor-status {
            z-index: 2;
            text-align: right;
        }

        /* Modern Stat Cards */
        .stat-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
            background: #ffffff;
        }

        .stat-icon {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        .icon-green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
        .icon-yellow { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        .icon-red { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

        .stat-content .stat-val {
            font-family: 'Outfit', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
            color: var(--dark);
            margin-bottom: 4px;
        }
        .stat-content .stat-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Glass Table Card */
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
            margin-top: 30px;
        }

        .panel-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .custom-table th {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            padding: 0 16px 8px 16px;
            border: none;
        }

        .custom-table td {
            background: #ffffff;
            padding: 16px;
            vertical-align: middle;
            font-size: 0.9rem;
            font-weight: 500;
            border: none;
            transition: 0.2s;
        }
        
        .custom-table tr td:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
        .custom-table tr td:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }

        .custom-table tr:hover td {
            background: #f8fafc;
            transform: scale(1.005);
            box-shadow: 0 5px 15px rgba(0,0,0,0.02);
        }

        /* Fancy Badges */
        .bdg {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .bdg-success { background: rgba(16, 185, 129, 0.1); color: #059669; }
        .bdg-warning { background: rgba(245, 158, 11, 0.1); color: #d97706; }
        .bdg-danger { background: rgba(239, 68, 68, 0.1); color: #dc2626; }
        
        /* Logout btn */
        .btn-glass-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 30px;
            transition: all 0.2s;
        }
        .btn-glass-danger:hover {
            background: #ef4444;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        @media (max-width: 768px) {
            .profile-banner { flex-direction: column; text-align: center; padding: 30px 20px; gap: 15px;}
            .sensor-status { text-align: center; margin-top: 10px; }
            .badge-info { justify-content: center; }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="glass-navbar">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div style="width:40px; height:40px; background:white; border-radius:10px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
                <img src="{{ asset('img/mts.WEBP') }}" alt="Logo" style="width: 28px; height: 28px; object-fit: contain;">
            </div>
            <div>
                <h5 class="navbar-brand-text">PortalSiswa</h5>
                <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600; letter-spacing: 0.5px;">MTs Mambaul Ulum</div>
            </div>
        </div>
        <a href="{{ route('logout') }}" class="btn-glass-danger text-decoration-none" onclick="return confirm('Anda yakin ingin keluar?')">
            <i class="fa-solid fa-power-off me-1"></i> Keluar
        </a>
    </div>
</nav>

<div class="container">

<!-- Profile Banner -->
    <div class="profile-banner">
        <div class="avatar-glow">
            <i class="fa-solid fa-user-astronaut"></i>
        </div>
        <div class="profile-info">
            <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.6); margin-bottom: 5px; font-weight: 600;">Selamat Datang,</div>
            <h1 class="profile-name">{{ $guruData->name }}</h1>
            <div class="d-flex gap-3 flex-wrap mt-3">
                <div class="badge-info">
                    <i class="fa-regular fa-envelope" style="color: #6ee7b7;"></i> {{ $guruData->email }}
                </div>
                <div class="badge-info">
                    <i class="fa-solid fa-chalkboard-user" style="color: #93c5fd;"></i> Role: {{ strtoupper($guruData->role) }}
                </div>
            </div>
            <div class="mt-4 d-flex gap-2 flex-wrap">
                <a href="{{ url('/data-siswa') }}" class="btn btn-light rounded-pill px-4 py-2" style="font-weight: 600; font-size: 0.85rem; color: #0f172a; box-shadow: 0 4px 15px rgba(255,255,255,0.2);">
                    <i class="fa-solid fa-users me-1"></i> Data Siswa
                </a>
                <a href="{{ url('/siswa?view=full') }}" class="btn btn-outline-light rounded-pill px-4 py-2" style="font-weight: 600; font-size: 0.85rem;">
                    <i class="fa-solid fa-desktop me-1"></i> Monitoring Lengkap
                </a>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-4 mt-2">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;"><i class="fa-solid fa-users"></i></div>
                <div class="stat-content">
                    <div class="stat-val">{{ $totalSiswa }}</div>
                    <div class="stat-label">Total Siswa</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon icon-green"><i class="fa-solid fa-user-check"></i></div>
                <div class="stat-content">
                    <div class="stat-val">{{ $totalHadir }}</div>
                    <div class="stat-label">Siswa Hadir</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon icon-yellow"><i class="fa-solid fa-envelope-open-text"></i></div>
                <div class="stat-content">
                    <div class="stat-val">{{ $totalIzin }}</div>
                    <div class="stat-label">Izin / Sakit</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon icon-red"><i class="fa-solid fa-user-xmark"></i></div>
                <div class="stat-content">
                    <div class="stat-val">{{ $totalAlpa }}</div>
                    <div class="stat-label">Total Alpa</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="glass-panel">
        <div class="panel-title">
            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, var(--secondary), #818cf8); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.9rem;">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            Live Feed Presensi (20 Terakhir)
        </div>

        <div class="table-responsive" style="overflow-x: visible;">
            <table id="tableGuruAbsensi" class="custom-table table table-borderless align-middle w-100">
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Waktu Presensi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</div>


<!-- Modal Pengajuan Izin -->
<div class="modal fade" id="modalIzinSiswa" style="display:none;" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
                <h5 class="modal-title fw-bold" style="color: var(--dark); font-family: Outfit, sans-serif;">
                    <i class="fa-solid fa-paper-plane text-primary me-2"></i> Pengajuan Absen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route("siswa.pengajuan_izin") }}" method="POST">
                @csrf
                <div class="modal-body" style="padding: 24px;">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Pilih Jenis Pengajuan</label>
                        <div class="d-flex gap-3">
                            <div class="form-check flex-fill" style="background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.2); padding: 12px 16px 12px 40px; border-radius: 12px;">
                                <input class="form-check-input" type="radio" name="status" id="statusIzin" value="Izin" required>
                                <label class="form-check-label fw-bold text-warning-emphasis" for="statusIzin">
                                    Izin
                                </label>
                            </div>
                            <div class="form-check flex-fill" style="background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.2); padding: 12px 16px 12px 40px; border-radius: 12px;">
                                <input class="form-check-input" type="radio" name="status" id="statusSakit" value="Sakit" required>
                                <label class="form-check-label fw-bold text-success" for="statusSakit">
                                    Sakit
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label fw-bold text-muted small text-uppercase">Alasan / Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="3" placeholder="Tuliskan keterangan detail di sini..." required style="border-radius: 12px; border: 1px solid #e2e8f0; padding: 12px; background: #f8fafc;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none; padding: 0 24px 24px 24px;">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal" style="font-weight: 600;">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4" style="background: var(--primary); border: none; font-weight: 600; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
    import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-database.js";

    const firebaseConfig = {
        apiKey: "AIzaSyA...", 
        authDomain: "presensimts-80d6a.firebaseapp.com",
        databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}", 
        projectId: "presensimts-80d6a",
        storageBucket: "presensimts-80d6a.appspot.com",
        messagingSenderId: "1234567890", 
        appId: "1:123456:web:abcdef"      
    };

    const app = initializeApp(firebaseConfig);
    const database = getDatabase(app);
    const dbRef = ref(database, 'scan_fingerprint');
    
    let initialLoad = true;

    $(document).ready(function() {
        window.tableGuruAbsensi = $('#tableGuruAbsensi').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('api.log.absensi.guru') }}',
            columns: [
                {data: 'nama_siswa', name: 'nama_siswa'},
                {data: 'waktu_presensi', name: 'waktu_presensi', orderable: false, searchable: false},
                {data: 'status', name: 'status', orderable: false, searchable: false}
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
            },
            pageLength: 20,
            ordering: false
        });
        
        onValue(dbRef, (snapshot) => {
            const data = snapshot.val();
            if (data) {
                if (initialLoad) {
                    initialLoad = false;
                    return;
                }
                
                if (window.tableGuruAbsensi) {
                    window.tableGuruAbsensi.ajax.reload(null, false);
                }
            }
        });
    });
</script>
    @include('partials.sweetalerts')
</body>
</html>
