<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Alat - MTs Mambaul Ulum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

        /* SIDEBAR STYLE - SAMA KAYA DATA SISWA */
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

        /* AREA KONTEN UTAMA */
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
                <img src="{{ asset('img/mts.webp') }}" alt="Logo MTS Mambaul Ulum" class="w-100 h-100 object-fit-cover">
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
        <a class="nav-link-custom {{ Request::is('data-kelas') ? 'active' : '' }}" href="{{ route('data.kelas') }}">
            <i class="fa-solid fa-layer-group"></i> Data Manajemen Kelas
        </a>
        <a class="nav-link-custom {{ Request::is('siswa/rekap-pdf') || Request::is('admin/rekap-pdf') ? 'active' : '' }}" href="{{ route('siswa.rekap_pdf') }}">
            <i class="fa-solid fa-file-invoice"></i> Rekap Presensi / Laporan
        </a>

        <div class="menu-section-title">Konfigurasi</div>
        <a class="nav-link-custom {{ Request::is('devices*') ? 'active' : '' }}" href="{{ route('devices.index') }}">
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
                <h2 class="panel-title">Manajemen Alat Fingerprint</h2>
                <span class="panel-subtitle">Daftarkan IP ESP32 dan cek status koneksi alat secara realtime</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                @if(session('user_role') === 'admin' || session('user_role') === 'kepsek')
                <button class="btn btn-success fw-bold px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" style="font-size: 0.88rem; background-color: var(--accent-green); border:none;" data-bs-toggle="modal" data-bs-target="#modalTambahAlat">
                    <i class="fa-solid fa-plus fs-5"></i> Tambah Alat
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

        <div class="table-container-card">
            <h5 class="fw-bold text-dark mb-4">Daftar Alat Terdaftar</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary" style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">
                        <tr>
                            <th scope="col" class="px-3 py-3" style="width: 80px;">No</th>
                            <th scope="col" class="py-3">Nama Alat</th>
                            <th scope="col" class="py-3">IP Address</th>
                            <th scope="col" class="py-3">Device Token</th>
                            <th scope="col" class="py-3">Status</th>
                            <th scope="col" class="py-3">Last Ping</th>
                            <th scope="col" class="py-3 text-center" style="width: 250px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.9rem; color: #475569;">
                        @forelse($devices as $index => $d)
                        <tr>
                            <td class="px-3 fw-bold font-mono-custom">{{ $index + 1 }}</td>
                            <td class="fw-semibold text-dark">
                                <i class="fa-solid fa-fingerprint text-success me-2"></i>{{ $d->nama_alat }}
                            </td>
                            <td><code class="font-mono-custom">{{ $d->ip_address }}</code></td>
                            <td>
                                <code class="font-mono-custom text-primary bg-primary bg-opacity-10 px-2 py-1 rounded" style="font-size: 0.75rem;">
                                    {{ $d->device_token ?? 'Belum ada token' }}
                                </code>
                            </td>
                            <td>
                                <span class="badge px-2.5 py-1.5 fw-semibold {{ $d->status == 'Online' || $d->status == 'scan' ? 'bg-success bg-opacity-10 text-success border-success' : ($d->status == 'enroll' || $d->status == 'delete' ? 'bg-warning bg-opacity-10 text-warning border-warning' : ($d->status == 'lock' ? 'bg-danger bg-opacity-10 text-danger border-danger' : 'bg-secondary bg-opacity-10 text-secondary border')) }}">
                                    <i class="fa-solid fa-circle-dot me-1" style="font-size: 0.6rem;"></i>{{ strtoupper($d->status) }}
                                </span>
                            </td>
                            <td class="text-muted font-mono-custom">
                                {{ $d->last_ping ? \Carbon\Carbon::parse($d->last_ping)->diffForHumans() : '-' }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('devices.ping', $d->id) }}" class="btn btn-sm btn-outline-primary px-2.5 py-1.5 rounded-3" title="Cek Koneksi">
                                        <i class="fa-solid fa-wifi"></i>
                                    </a>
                                    
                                    @if(session('user_role') === 'admin' || session('user_role') === 'kepsek')
                                    @php $fScan = $premiumFeatures['aksi-mode-scan'] ?? null; @endphp
                                    @if($fScan && $fScan->is_active && !$fScan->is_unlocked && !($fScan->has_demo && $fScan->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($fScan->demo_expires_at))))
                                    <button type="button" class="btn btn-sm btn-outline-info px-2.5 py-1.5 rounded-3" title="Kembalikan ke Mode Scan (Premium)" onclick="showPaywall('{{ $fScan->nama_fitur }}', '{{ $fScan->harga }}', '{{ $fScan->has_demo }}', '{{ $fScan->demo_requested }}', '{{ $fScan->demo_expires_at }}', '{{ $fScan->menu_code }}', '{{ $fScan->max_demo_requests }}', '{{ $fScan->demo_used_count }}', '{{ !empty($fScan->payment_requested) ? 1 : 0 }}')">
                                        <i class="fa-solid fa-rotate-right"></i>
                                    </button>
                                    @else
                                    <form action="{{ route('devices.force_scan', $d->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-info px-2.5 py-1.5 rounded-3" title="Kembalikan ke Mode Scan" onclick="return confirm('Paksa alat {{ $d->nama_alat }} kembali ke mode scan (membatalkan enroll/delete/lock yang menggantung)?')">
                                            <i class="fa-solid fa-rotate-right"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @php $fLock = $premiumFeatures['aksi-kunci-alat'] ?? null; @endphp
                                    @if($fLock && $fLock->is_active && !$fLock->is_unlocked && !($fLock->has_demo && $fLock->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($fLock->demo_expires_at))))
                                    <button type="button" class="btn btn-sm btn-outline-danger px-2.5 py-1.5 rounded-3" title="Kunci Alat (Premium)" onclick="showPaywall('{{ $fLock->nama_fitur }}', '{{ $fLock->harga }}', '{{ $fLock->has_demo }}', '{{ $fLock->demo_requested }}', '{{ $fLock->demo_expires_at }}', '{{ $fLock->menu_code }}', '{{ $fLock->max_demo_requests }}', '{{ $fLock->demo_used_count }}', '{{ !empty($fLock->payment_requested) ? 1 : 0 }}')">
                                        <i class="fa-solid fa-lock"></i>
                                    </button>
                                    @else
                                    <form action="{{ route('devices.lock', $d->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $d->status === 'lock' ? 'btn-danger' : 'btn-outline-danger' }} px-2.5 py-1.5 rounded-3" title="Kunci Alat (Lockdown)" onclick="return confirm('Kunci alat {{ $d->nama_alat }}? Alat tidak akan menerima absen sampai dinormalkan kembali.')">
                                            <i class="fa-solid fa-lock"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <form action="{{ route('devices.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Hapus alat {{ $d->nama_alat }} dari web?')" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1.5 rounded-3" title="Hapus Alat">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fa-solid fa-fingerprint fa-2x mb-3 d-block opacity-25"></i>
                                Belum ada alat terdaftar. Klik "Tambah Alat" untuk mulai.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahAlat" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-style-content">
                <div class="modal-header modal-style-header">
                    <h5 class="modal-title fw-bold" style="font-size:1.05rem;"><i class="fa-solid fa-fingerprint me-2"></i>Tambah Alat Fingerprint</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('devices.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nama Alat</label>
                            <input type="text" name="nama_alat" class="form-control py-2.5" placeholder="Contoh: Ruang Guru, Kelas VII-A" style="border-radius:8px;" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">IP Address ESP32</label>
                            @php
                                $localIp = getHostByName(getHostName());
                                $ipPrefix = '';
                                if ($localIp !== '127.0.0.1' && $localIp !== '::1') {
                                    $parts = explode('.', $localIp);
                                    if (count($parts) === 4) {
                                        array_pop($parts);
                                        $ipPrefix = implode('.', $parts) . '.';
                                    }
                                }
                            @endphp
                            <input type="text" id="ip_address_input" name="ip_address" class="form-control py-2.5 font-mono-custom" placeholder="Contoh: 192.168.1.50" value="{{ $ipPrefix }}" style="border-radius:8px;" required>
                            <small class="text-muted d-block mt-2">
                                <i class="fa-solid fa-circle-info text-info me-1"></i>
                                Prefix IP Jaringan WiFi Anda terdeteksi: <strong>{{ $ipPrefix ?: 'Tidak diketahui' }}</strong><br>
                                Pastikan ESP32 terhubung ke jaringan yang sama.
                            </small>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2.5 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background-color: var(--sidebar-bg); border: none;">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Alat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto Refresh Status Alat (AJAX) -->
    <script>
        setInterval(() => {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Ambil isi tbody dari hasil fetch
                    const newTbody = doc.querySelector('tbody');
                    const oldTbody = document.querySelector('tbody');
                    
                    if (newTbody && oldTbody) {
                        oldTbody.innerHTML = newTbody.innerHTML;
                    }
                })
                .catch(err => console.error('Gagal memuat status alat:', err));
        }, 3000); // Update setiap 3 detik
    </script>

<script>
    // Inisialisasi tooltip bootstrap jika ada
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
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
function showPaywall(name, price, has_demo, demo_requested, expires_at, menu_code, max_demo, used_demo) {
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
    
    // Insert before the close button
    const closeBtn = modalBody.querySelector('.btn-secondary');
    modalBody.insertBefore(demoContainer, closeBtn);

    var myModal = new bootstrap.Modal(document.getElementById('modalPremiumPaywall'));
    myModal.show();
}
</script>

    @include('partials.sweetalerts')
</body>
</html>
