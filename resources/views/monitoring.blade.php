    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard Monitoring Presensi Real-Time</title>

        <script src="{{ asset('js/tailwindcss.js') }}"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <style>
            /* --- RESET & BASIC STYLING --- */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            body {
                display: flex;
                background-color: #f8fafc;
                min-height: 100vh;
                color: #1e293b;
                overflow-x: hidden;
            }

            /* --- SIDEBAR --- */
            .sidebar {
                width: 280px;
                background: linear-gradient(180deg, #1b4d22 0%, #143a1a 100%);
                color: white;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                position: fixed;
                box-shadow: 6px 0 30px rgba(0, 0, 0, 0.12);
                z-index: 100;
            }

            .brand-wrapper {
                padding: 24px 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 12px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
                background: rgba(0, 0, 0, 0.15);
            }

            .brand-logo-frame {
                width: 85px; 
                height: 85px; 
                object-fit: contain;
                background: white;
                padding: 6px;
                border-radius: 50%; 
                box-shadow: 0 6px 16px rgba(0,0,0,0.25);
                transition: transform 0.3s ease;
                border: 3px solid rgba(255, 255, 255, 0.2);
            }

            .brand-text {
                display: flex;
                flex-direction: column;
                gap: 2px;
            }

            .brand-title {
                font-size: 14px; 
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                line-height: 1.4;
                color: #ffffff;
            }

            .brand-subtitle {
                font-size: 11px; 
                font-weight: 600;
                color: #34d399; 
                letter-spacing: 0.5px;
            }

            .menu-container {
                padding: 24px 0;
                flex-grow: 1;
                overflow-y: auto;
            }

            .menu-group-title {
                font-size: 11px; 
                text-transform: uppercase;
                letter-spacing: 1.5px;
                color: rgba(255, 255, 255, 0.45);
                padding: 0 24px 12px 24px;
                font-weight: 700;
            }

            .menu-link {
                display: flex;
                align-items: center;
                gap: 12px; 
                padding: 14px 24px; 
                color: rgba(255, 255, 255, 0.75);
                text-decoration: none;
                font-size: 14px; 
                font-weight: 500;
                transition: all 0.3s ease;
                border-left: 5px solid transparent;
            }

            .menu-link i {
                font-size: 16px; 
                width: 20px;
                text-align: center;
            }

            .menu-link:hover {
                background: rgba(255, 255, 255, 0.06);
                color: white;
            }

            .menu-link.active {
                background: linear-gradient(90deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.02) 100%);
                color: white;
                font-weight: 600;
                border-left-color: #34d399;
            }

            .sidebar-footer {
                border-top: 1px solid rgba(255, 255, 255, 0.08);
                padding: 15px 0;
                background: rgba(0, 0, 0, 0.1);
            }

            .btn-logout {
                color: #fca5a5 !important;
            }

            /* --- KONTEN UTAMA --- */
            .main-wrapper {
                margin-left: 280px;
                flex-grow: 1;
                padding: 40px;
                max-width: calc(100% - 280px);
            }

            .top-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 35px;
                background: white;
                padding: 18px 30px;
                border-radius: 16px;
                box-shadow: 0 4px 18px rgba(15, 23, 42, 0.02);
                border: 1px solid #f1f5f9;
            }

            .header-title-area h1 {
                font-size: 20px;
                font-weight: 700;
                color: #0f172a;
            }

            .header-title-area p {
                font-size: 12px;
                color: #64748b;
            }

            .btn-pdf-premium {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: white;
                padding: 8px 16px;
                text-decoration: none;
                border-radius: 10px;
                font-weight: 600;
                font-size: 12px;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
            }

            .profile-card {
                display: flex;
                align-items: center;
                gap: 12px;
                padding-left: 20px;
                border-left: 1px solid #e2e8f0;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 24px;
                margin-bottom: 35px;
            }

            .stat-card {
                background: white;
                border-radius: 16px;
                padding: 24px;
                border: 1px solid #f1f5f9;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .stat-value {
                font-size: 26px;
                font-weight: 800;
                color: #0f172a;
            }

            .stat-label {
                font-size: 12px;
                color: #64748b;
            }

            .stat-icon-box {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
            }

            .icon-total { background: #f0fdf4; color: #16a34a; }
            .icon-hadir { background: #eff6ff; color: #2563eb; }
            .icon-izin { background: #fef3c7; color: #d97706; }

            /* --- NOTIFIKASI --- */
            .alert-premium {
                background: #ecfdf5;
                color: #065f46;
                padding: 16px 24px;
                border-radius: 12px;
                margin-bottom: 15px;
                font-size: 13.5px;
                display: flex;
                align-items: center;
                gap: 12px;
                border: 1px solid #a7f3d0;
            }

            /* --- TABEL LOG ABSENSI --- */
            .table-card {
                background: white;
                border-radius: 20px;
                padding: 30px;
                border: 1px solid #f1f5f9;
            }

            .table-header-flex {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 24px;
            }

            .table-card-title {
                font-size: 16px;
                font-weight: 700;
                color: #0f172a;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th {
                background-color: #f8fafc;
                color: #475569;
                text-transform: uppercase;
                font-size: 11px;
                font-weight: 700;
                padding: 16px 20px;
                border-bottom: 2px solid #e2e8f0;
            }

            td {
                padding: 16px 20px;
                font-size: 13.5px;
                color: #334155;
                border-bottom: 1px solid #f1f5f9;
            }

            /* Badge Status */
            .status-badge {
                padding: 6px 12px;
                border-radius: 8px;
                font-size: 11.5px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }
            .badge-premium-hadir { background: #e0f2fe; color: #0369a1; }
            .badge-premium-izin { background: #dcfce7; color: #15803d; }
            .badge-premium-absen { background: #fee2e2; color: #b91c1c; }

            /* --- TOMBOL INPUT IZIN --- */
            .btn-action-izin {
                background-color: #3b82f6; 
                color: white;
                border: none;
                padding: 8px 14px;
                font-size: 12px;
                font-weight: 600;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.2s ease;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                box-shadow: 0 4px 10px rgba(59, 130, 246, 0.15);
            }

            .btn-action-izin:hover {
                background-color: #2563eb;
                transform: translateY(-1px);
            }

            /* --- TOMBOL INPUT ALPHA (TAMBAHAN BARU) --- */
            .btn-action-alpha {
                background-color: #ef4444; 
                color: white;
                border: none;
                padding: 8px 14px;
                font-size: 12px;
                font-weight: 600;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.2s ease;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                box-shadow: 0 4px 10px rgba(239, 68, 68, 0.15);
            }

            .btn-action-alpha:hover {
                background-color: #dc2626;
                transform: translateY(-1px);
            }

            /* --- MODAL DIALOG POPUP --- */
            .modal-overlay {
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(4px);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                opacity: 0; pointer-events: none;
                transition: all 0.3s ease;
            }

            .modal-overlay.active {
                opacity: 1; pointer-events: auto;
            }

            .modal-box {
                background: white;
                border-radius: 16px;
                width: 100%;
                max-width: 450px;
                padding: 30px;
            }

            .modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }

            .form-group { margin-bottom: 18px; }
            .form-label { display: block; font-size: 12.5px; font-weight: 600; color: #475569; margin-bottom: 8px; }
            .form-input { width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13.5px; outline: none; }

            /* Tombol Simpan Form Terintegrasi Kirim WhatsApp */
            .btn-submit-wa { 
                width: 100%; 
                background: #25d366; 
                color: white; 
                border: none; 
                padding: 12px; 
                border-radius: 8px; 
                font-weight: 700; 
                font-size: 13.5px; 
                cursor: pointer; 
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                box-shadow: 0 4px 14px rgba(37, 211, 102, 0.25);
            }
            .btn-submit-wa:hover { background: #1ebd58; }
        </style>
    </head>
    <body>

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

                {{-- Perbaikan Sidebar Menu: Hak Akses Rekap Laporan untuk Admin dan Kepsek --}}
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

        <div class="main-wrapper">
            <div class="top-header">
                <div class="header-title-area">
                    <h1>Sistem Monitoring Absening</h1>
                    <p>Data Sinkronisasi Otomatis Mesin Fingerprint Cloud</p>
                </div>
                <div class="header-action-area" style="display: flex; align-items: center; gap: 20px;">
                    <a href="{{ route('admin.rekap.pdf') }}" target="_blank" class="btn-pdf-premium">
                        <i class="fa-solid fa-file-pdf"></i> Cetak Rekap PDF
                    </a>
                    <div class="profile-card">
                        <div style="text-align: right;">
                            {{-- Menampilkan Nama & Role login secara dinamis --}}
                            <div style="font-size: 13px; font-weight: 700;">{{ session('user_name', 'User') }}</div>
                            <div style="font-size: 11px; color: #10b981; font-weight: 600;">{{ strtoupper(session('user_role', 'USER')) }}</div>
                        </div>
                    </div>
                </div>
            </div>
<div class="stats-grid">
                <div class="stat-card">
                    <div>
                        <div class="stat-value">{{ $totalSiswa ?? '0' }}</div>
                        <div class="stat-label">Total Siswa Terdaftar</div>
                    </div>
                    <div class="stat-icon-box icon-total"><i class="fa-solid fa-users"></i></div>
                </div>
                <div class="stat-card">
                    <div>
                        <div class="stat-value">{{ $totalHadir ?? '0' }}</div>
                        <div class="stat-label">Siswa Hadir Hari Ini</div>
                    </div>
                    <div class="stat-icon-box icon-hadir"><i class="fa-solid fa-fingerprint"></i></div>
                </div>
                <div class="stat-card">
                    <div>
                        <div class="stat-value">{{ $totalIzin ?? '0' }}</div>
                        <div class="stat-label">Siswa Izin / Sakit</div>
                    </div>
                    <div class="stat-icon-box icon-izin"><i class="fa-solid fa-envelope-open-text"></i></div>
                </div>
            </div>

            <div class="table-card">
                <div class="table-header-flex">
                    <div class="table-card-title">Log Absensi Kehadiran Real-time</div>
                    <span style="font-size: 12px; font-weight: 600; color: #10b981; background: #e6fbf3; padding: 4px 10px; border-radius: 6px;">
                        ● Live Sync Connected
                    </span>
                </div>

                <div style="width: 100%; overflow-x: auto;">
                    <table id="tableLogAbsensi" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>ID Siswa / NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th style="width: 140px;">Jam Masuk</th>
                                <th style="width: 140px;">Jam Pulang</th>
                                <th style="text-align: center; width: 140px;">Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal-overlay" id="modalIzin">
            <div class="modal-box">
                <div class="modal-header">
                    <div style="font-size: 16px; font-weight: 700; color: #0f172a;">Form Keterangan Tidak Hadir</div>
                    <button onclick="closeModalIzin()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #94a3b8;">&times;</button>
                </div>

                <form action="{{ route('attendance.storeManual') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">ID / Kode Siswa</label>
                        <input type="text" name="siswa_id" id="modal_siswa_id" class="form-input" readonly required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ubah Status Menjadi</label>
                        <select name="status" class="form-input" style="background-color: white;" required>
                            <option value="Izin">✉️ Izin (Ada Surat)</option>
                            <option value="Sakit">🤢 Sakit</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alasan / Keterangan Tambahan</label>
                        <input type="text" name="keterangan" class="form-input" placeholder="Contoh: Sakit Demam / Acara Keluarga" required>
                    </div>

                    <button type="submit" class="btn-submit-wa">
                        <i class="fa-brands fa-whatsapp" style="font-size: 16px;"></i> Simpan & Kirim Notifikasi WA
                    </button>
                </form>
            </div>
        </div>

        <div class="modal-overlay" id="modalAlpha">
            <div class="modal-box">
                <div class="modal-header">
                    <div style="font-size: 16px; font-weight: 700; color: #1e293b;">Konfirmasi Absensi Alpha</div>
                    <button onclick="closeModalAlpha()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #94a3b8;">&times;</button>
                </div>

                <form action="{{ route('attendance.storeManual') }}" method="POST">
                    @csrf
                    <input type="hidden" name="siswa_id" id="modal_alpha_siswa_id">
                    <input type="hidden" name="status" value="Alpa">
                    <input type="hidden" name="keterangan" value="Tanpa Keterangan">

                    <div style="padding: 10px 0 20px 0; text-align: left; font-size: 14px; color: #475569; line-height: 1.6;">
                        Apakah Anda yakin ingin memberi status <strong class="text-red-600">Alpha (Tanpa Keterangan)</strong> pada siswa ini? Tindakan ini akan langsung tersimpan ke database rekap dan memicu pengiriman notifikasi via WhatsApp ke orang tua siswa.
                    </div>

                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" onclick="closeModalAlpha()" style="background: #e2e8f0; color: #475569; padding: 10px 16px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;">
                            Batal
                        </button>
                        <button type="submit" class="btn-submit-wa" style="width: auto; padding: 10px 20px;">
                            <i class="fa-brands fa-whatsapp" style="font-size: 16px;"></i> Ya, Kirim WA & Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openModalIzin(siswaId) {
                document.getElementById('modal_siswa_id').value = siswaId;
                document.getElementById('modalIzin').classList.add('active');
            }

            function closeModalIzin() {
                document.getElementById('modalIzin').classList.remove('active');
            }

            function openModalAlpha(siswaId) {
                document.getElementById('modal_alpha_siswa_id').value = siswaId;
                document.getElementById('modalAlpha').classList.add('active');
            }

            function closeModalAlpha() {
                document.getElementById('modalAlpha').classList.remove('active');
            }

            // Real-time UI dipicu oleh Firebase (lihat script type="module" di bawah)
            $(document).ready(function() {
                window.tableLogAbsensi = $('#tableLogAbsensi').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('api.log.absensi') }}',
                        error: function(xhr, error, code) {
                            console.error('DataTables Ajax Error [monitoring]:', error, code, xhr.responseText);
                        }
                    },
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'nis', name: 'nis'},
                        {data: 'nama_siswa', name: 'nama_siswa'},
                        {data: 'kelas', name: 'kelas'},
                        {data: 'waktu_masuk', name: 'waktu_masuk'},
                        {data: 'waktu_pulang', name: 'waktu_pulang'},
                        {data: 'aksi', name: 'aksi', orderable: false, searchable: false}
                    ]
                });
            });
        </script>
        @include('partials.sweetalerts')

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
        import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-database.js";

        const firebaseConfig = {
            apiKey: "{{ env('FIREBASE_API_KEY') }}", 
            authDomain: "{{ env('FIREBASE_AUTH_DOMAIN', 'presensimts-80d6a.firebaseapp.com') }}",
            databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}", 
            projectId: "{{ env('FIREBASE_PROJECT_ID', 'presensimts-80d6a') }}",
            storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET', 'presensimts-80d6a.appspot.com') }}",
            messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}", 
            appId: "{{ env('FIREBASE_APP_ID') }}"      
        };

        const app = initializeApp(firebaseConfig);
        const database = getDatabase(app);
        const dbRef = ref(database, 'scan_fingerprint');

        let initialLoad = true;

        onValue(dbRef, (snapshot) => {
            const data = snapshot.val();
            if (data) {
                console.log("Data Presensi Baru Masuk dari Firebase:", data);

                // Cegah penambahan baris saat halaman pertama kali dibuka
                if (initialLoad) {
                    initialLoad = false;
                    return;
                }

                // Reload datatable ketika ada scan fingerprint baru
                if (window.tableLogAbsensi) {
                    window.tableLogAbsensi.ajax.reload(null, false);
                }

                // Update counter statistik dengan halus
                if (data.status && data.status === 'Masuk') {
                    const statBox = document.querySelector('.icon-hadir')?.parentElement;
                    if (statBox) {
                        const valEl = statBox.querySelector('.stat-value');
                        if (valEl) valEl.innerText = parseInt(valEl.innerText) + 1;
                        
                        // Efek flash hijau sebentar
                        statBox.style.transition = 'background-color 0.3s ease';
                        statBox.style.backgroundColor = '#d1fae5';
                        setTimeout(() => statBox.style.backgroundColor = 'white', 600);
                    }
                }
            }
        });
    </script>
</body>
</html>