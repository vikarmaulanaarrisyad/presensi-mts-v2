<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
        .sidebar { width: 280px; height: 100vh; position: fixed; background-color: #0f172a; padding-top: 20px; color: white; }
        .main-content { margin-left: 280px; padding: 30px; }
        .card-premium { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .card-header-premium { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; border-radius: 15px 15px 0 0 !important; padding: 20px; }
        .form-control { border-radius: 8px; padding: 12px; }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column">
        <div class="px-4 mb-4 text-center">
            <h4 class="fw-bold mb-0 text-warning"><i class="fa-solid fa-crown me-2"></i>Super Admin</h4>
            <small class="text-secondary">Control Panel</small>
        </div>
        <hr class="border-secondary mb-4">
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link text-white px-4 py-3 fw-semibold bg-white bg-opacity-10 border-start border-4 border-warning">
            <i class="fa-solid fa-gem me-3"></i> Fitur Premium
        </a>
        <a href="{{ route('superadmin.monitoring') }}" class="nav-link text-white px-4 py-3 fw-semibold" style="opacity:.8">
            <i class="fa-solid fa-server me-3 text-info"></i> Server Monitoring
        </a>
        <div class="mt-auto p-4">
            <a href="{{ route('logout') }}" class="btn btn-outline-danger w-100 fw-bold border-2 rounded-3"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark m-0">Pengaturan Fitur Prabayar</h2>
            <div class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold"><i class="fa-solid fa-bolt me-1"></i> Mode Superadmin</div>
        </div>
<div class="row">
            <div class="col-lg-8">
                <div class="card card-premium">
                    <div class="card-header-premium d-flex align-items-center">
                        <i class="fa-solid fa-box-open fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">Konfigurasi Penawaran Premium</h5>
                            <small>Data ini akan ditampilkan di Dashboard Admin jika fitur diaktifkan.</small>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('superadmin.features.update') }}" method="POST">
                            @csrf
                            
                            <!-- INFORMASI REKENING GLOBAL -->
                            <div class="bg-light p-3 rounded-3 border mb-4">
                                <h6 class="fw-bold mb-3"><i class="fa-solid fa-building-columns me-2"></i>Informasi Rekening Pembayaran (Global)</h6>
                                
                                @foreach($payments as $p)
                                <div class="row g-2 mb-3 border-bottom pb-2">
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted fw-bold">Nama Bank</label>
                                        <input type="text" name="payments[{{ $p->id }}][nama_bank]" class="form-control" value="{{ $p->nama_bank }}" placeholder="BCA / Mandiri">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted fw-bold">No. Rekening</label>
                                        <input type="text" name="payments[{{ $p->id }}][no_rekening]" class="form-control font-monospace" value="{{ $p->no_rekening }}" placeholder="1234567890">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted fw-bold">Atas Nama (A.N)</label>
                                        <input type="text" name="payments[{{ $p->id }}][atas_nama]" class="form-control" value="{{ $p->atas_nama }}" placeholder="A.N Siapa">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger w-100" onclick="if(confirm('Hapus rekening ini?')) { document.getElementById('delete-payment-{{ $p->id }}').submit(); }">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach

                                <div class="row g-2" id="new-payment-row">
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted fw-bold">Tambah Bank Baru</label>
                                        <input type="text" name="new_payments[0][nama_bank]" class="form-control border-primary" placeholder="BCA / Mandiri">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted fw-bold">No. Rekening</label>
                                        <input type="text" name="new_payments[0][no_rekening]" class="form-control font-monospace border-primary" placeholder="1234567890">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small text-muted fw-bold">Atas Nama (A.N)</label>
                                        <input type="text" name="new_payments[0][atas_nama]" class="form-control border-primary" placeholder="A.N Siapa">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Menu/Fitur</th>
                                            <th>Harga (Rp)</th>
                                            <th class="text-center">Jadikan Premium</th>
                                            <th class="text-center">Izinkan Demo?</th>
                                            <th class="text-center">Batas Kuota Demo</th>
                                            <th class="text-center">Status Lunas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($features as $f)
                                        <tr>
                                            <td class="fw-bold">{{ $f->nama_fitur }}</td>
                                            <td>
                                                <input type="text" name="features[{{ $f->id }}][harga]" class="form-control form-control-sm font-monospace" value="{{ $f->harga }}" placeholder="Contoh: 150000">
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input" type="checkbox" name="features[{{ $f->id }}][is_active]" style="cursor: pointer;" {{ $f->is_active ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input" type="checkbox" name="features[{{ $f->id }}][has_demo]" style="cursor: pointer;" {{ $f->has_demo ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <input type="number" name="features[{{ $f->id }}][max_demo_requests]" class="form-control form-control-sm text-center mx-auto" style="width: 70px;" value="{{ $f->max_demo_requests }}" min="1">
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input" type="checkbox" name="features[{{ $f->id }}][is_unlocked]" style="cursor: pointer;" {{ $f->is_unlocked ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <button type="submit" class="btn btn-dark w-100 py-3 fw-bold rounded-3 fs-5 shadow-sm mt-3">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Semua Konfigurasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fa-solid fa-shield-halved fa-4x text-primary opacity-50"></i>
                        </div>
                        <h5 class="fw-bold">Akses Terbatas</h5>
                        <p class="text-muted small">Halaman ini hanya dapat diakses oleh Superadmin. Segala perubahan yang disimpan di sini akan langsung berdampak pada tampilan sekolah/admin.</p>
                    </div>
                </div>

                <!-- DAFTAR PERMINTAAN DEMO -->
                <div class="card border-0 shadow-sm rounded-3 border-top border-warning border-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0">
                        <h5 class="fw-bold"><i class="fa-solid fa-bell text-warning me-2"></i> Permintaan Demo</h5>
                        <p class="text-muted small">Admin mengajukan demo untuk fitur berikut:</p>
                    </div>
                    <div class="card-body p-4 pt-2" id="pending-demos-container">
                        @php $pendingRequests = $features->where('demo_requested', true); @endphp
                        
                        @if($pendingRequests->isEmpty())
                            <div class="text-center py-4 text-muted">
                                <i class="fa-solid fa-check-circle fa-2x mb-2 text-success opacity-50"></i>
                                <p class="mb-0 small">Tidak ada permintaan demo saat ini.</p>
                            </div>
                        @else
                            @foreach($pendingRequests as $req)
                                <div class="bg-light p-3 rounded-3 mb-3 border">
                                    <h6 class="fw-bold text-dark">{{ $req->nama_fitur }}</h6>
                                    
                                    <form action="{{ route('superadmin.approve_demo', $req->menu_code) }}" method="POST" class="mt-3">
                                        @csrf
                                        <div class="input-group input-group-sm mb-2">
                                            <span class="input-group-text bg-white">Durasi</span>
                                            <input type="number" name="minutes" class="form-control text-center" value="60" min="1" required>
                                            <span class="input-group-text bg-white">Menit</span>
                                        </div>
                                        <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold">
                                            <i class="fa-solid fa-check me-1"></i> Setujui Demo
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <!-- DAFTAR PERMINTAAN PEMBAYARAN -->
                <div class="card border-0 shadow-sm rounded-3 border-top border-success border-4 mt-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0">
                        <h5 class="fw-bold"><i class="fa-solid fa-money-bill-wave text-success me-2"></i> Konfirmasi Pembayaran</h5>
                        <p class="text-muted small">Admin mengunggah bukti transfer untuk fitur:</p>
                    </div>
                    <div class="card-body p-4 pt-2">
                        @if(isset($pendingPayments) && $pendingPayments->isEmpty())
                            <div class="text-center py-4 text-muted">
                                <i class="fa-solid fa-check-circle fa-2x mb-2 text-success opacity-50"></i>
                                <p class="mb-0 small">Tidak ada permintaan konfirmasi pembayaran.</p>
                            </div>
                        @elseif(isset($pendingPayments))
                            @foreach($pendingPayments as $pay)
                                <div class="bg-light p-3 rounded-3 mb-3 border">
                                    <h6 class="fw-bold text-dark">{{ $pay->nama_fitur }}</h6>
                                    <div class="small text-muted mb-2"><i class="fa-solid fa-clock me-1"></i> {{ \Carbon\Carbon::parse($pay->created_at)->format('d/m/Y H:i') }}</div>
                                    
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#modalBuktiBayar{{ $pay->id }}">
                                        <i class="fa-solid fa-image me-1"></i> Lihat Bukti Transfer
                                    </button>

                                    <div class="d-flex gap-2">
                                        <form action="{{ route('superadmin.payment.approve', $pay->id) }}" method="POST" class="flex-grow-1 m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm w-100 fw-bold" onclick="return confirm('Terima pembayaran dan buka fitur permanen?')">
                                                <i class="fa-solid fa-check"></i> Terima
                                            </button>
                                        </form>
                                        <form action="{{ route('superadmin.payment.reject', $pay->id) }}" method="POST" class="flex-grow-1 m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm w-100 fw-bold" onclick="return confirm('Tolak pembayaran ini?')">
                                                <i class="fa-solid fa-xmark"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Modal Bukti Bayar -->
                                <div class="modal fade" id="modalBuktiBayar{{ $pay->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                                            <div class="modal-header bg-dark text-white border-0" style="border-radius: 15px 15px 0 0;">
                                                <h5 class="modal-title fw-bold">Bukti Transfer - {{ $pay->nama_fitur }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 text-center">
                                                <img src="{{ asset('storage/payments/' . $pay->bukti_bayar) }}" alt="Bukti Bayar" class="img-fluid rounded border mb-3" style="max-height: 400px; object-fit: contain;">
                                                <button type="button" class="btn btn-secondary w-100 fw-bold rounded-3" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- HISTORI DEMO -->
                <div class="card border-0 shadow-sm rounded-3 border-top border-info border-4 mt-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0">
                        <h5 class="fw-bold"><i class="fa-solid fa-clock-rotate-left text-info me-2"></i> Histori Demo</h5>
                        <p class="text-muted small">Status demo yang pernah atau sedang disetujui:</p>
                    </div>
                    <div class="card-body p-4 pt-2">
                        @php 
                            $historyDemos = $features->filter(function($f) {
                                return !empty($f->demo_expires_at);
                            }); 
                        @endphp
                        
                        @if($historyDemos->isEmpty())
                            <div class="text-center py-4 text-muted">
                                <i class="fa-solid fa-folder-open fa-2x mb-2 opacity-50"></i>
                                <p class="mb-0 small">Belum ada histori demo.</p>
                            </div>
                        @else
                            <ul class="list-group list-group-flush small">
                            @foreach($historyDemos as $hist)
                                @php
                                    $expires = \Carbon\Carbon::parse($hist->demo_expires_at);
                                    $isPast = \Carbon\Carbon::now()->greaterThan($expires);
                                @endphp
                                <li class="list-group-item px-0 py-2 border-bottom">
                                    <div class="fw-bold text-dark">{{ $hist->nama_fitur }}</div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <span class="text-muted" style="font-size: 0.75rem;">Berakhir: {{ $expires->format('d/m/Y H:i') }}</span>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($isPast)
                                                <span class="badge bg-danger">Kedaluwarsa</span>
                                            @else
                                                <span class="badge bg-success">Aktif</span>
                                            @endif
                                            <form action="{{ route('superadmin.reset_demo', $hist->menu_code) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-secondary p-1 lh-1" title="Reset Histori" onclick="return confirm('Yakin ingin mereset histori demo fitur ini?')">
                                                    <i class="fa-solid fa-rotate-left" style="font-size: 0.7rem;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hidden forms for deleting payments -->
    @foreach($payments as $p)
    <form id="delete-payment-{{ $p->id }}" action="{{ route('superadmin.payment.delete', $p->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    @endforeach

    <script>
        function checkPendingDemos() {
            fetch("{{ route('superadmin.api.pending_demos') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const container = document.getElementById('pending-demos-container');
                        if (data.data.length === 0) {
                            container.innerHTML = `
                                <div class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-check-circle fa-2x mb-2 text-success opacity-50"></i>
                                    <p class="mb-0 small">Tidak ada permintaan demo saat ini.</p>
                                </div>
                            `;
                        } else {
                            let html = '';
                            data.data.forEach(req => {
                                const actionUrl = `{{ url('/superadmin/approve-demo') }}/${req.menu_code}`;
                                html += `
                                    <div class="bg-light p-3 rounded-3 mb-3 border">
                                        <h6 class="fw-bold text-dark">${req.nama_fitur}</h6>
                                        <form action="${actionUrl}" method="POST" class="mt-3">
                                            @csrf
                                            <div class="input-group input-group-sm mb-2">
                                                <span class="input-group-text bg-white">Durasi</span>
                                                <input type="number" name="minutes" class="form-control text-center" value="60" min="1" required>
                                                <span class="input-group-text bg-white">Menit</span>
                                            </div>
                                            <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold">
                                                <i class="fa-solid fa-check me-1"></i> Setujui Demo
                                            </button>
                                        </form>
                                    </div>
                                `;
                            });
                            
                            // Prevent overwriting if user is typing (Optional enhancement)
                            container.innerHTML = html;
                        }
                    }
                })
                .catch(error => console.error('Error fetching pending demos:', error));
        }

        // Jalankan pengecekan setiap 10 detik
        setInterval(checkPendingDemos, 10000);
    </script>
    @include('partials.sweetalerts')
</body>
</html>
