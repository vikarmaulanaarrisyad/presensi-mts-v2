<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Terkunci - Fitur Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .lock-card { max-width: 500px; width: 100%; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; }
        .lock-header { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 40px 20px; text-align: center; color: white; position: relative; }
        .lock-icon { font-size: 60px; color: #fbbf24; margin-bottom: 15px; }
        .price-box { background-color: #fffbeb; border: 1px dashed #f59e0b; border-radius: 10px; padding: 15px; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="card lock-card">
        <div class="lock-header">
            <i class="fa-solid fa-lock lock-icon"></i>
            <h3 class="fw-bold m-0">Fitur Premium Terkunci</h3>
            <p class="text-secondary mt-2 mb-0">Menu <strong>{{ $feature->nama_fitur }}</strong> memerlukan akses khusus.</p>
        </div>
        <div class="card-body p-4 text-center">
            
            <p class="text-muted mb-4">Untuk membuka dan menggunakan fitur ini tanpa batas, silakan lakukan pembayaran untuk aktivasi modul.</p>
            
            <div class="price-box text-start">
                <div class="row mb-2 text-center border-bottom pb-2">
                    <div class="col-12 small text-muted fw-bold text-uppercase mb-1">Harga Aktivasi</div>
                    <div class="col-12 text-success fw-bold fs-3">Rp {{ number_format((int)$feature->harga, 0, ',', '.') }}</div>
                </div>
                <div class="row mb-2 mt-2">
                    <div class="col-5 text-muted small fw-bold">Bank Tujuan</div>
                    <div class="col-7 fw-bold">{{ $payment->nama_bank ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-muted small fw-bold">No. Rekening</div>
                    <div class="col-7 fw-bold text-primary font-monospace">{{ $payment->no_rekening ?? '-' }}</div>
                </div>
                <div class="row">
                    <div class="col-5 text-muted small fw-bold">Atas Nama</div>
                    <div class="col-7 fw-bold">{{ $payment->atas_nama ?? '-' }}</div>
                </div>
            </div>
            
            <div class="d-grid gap-2">
                @if($feature->has_demo)
                    @if($feature->demo_requested)
                        <div class="alert alert-info py-2 small mb-2 text-center">
                            <i class="fa-solid fa-hourglass-half me-1"></i> Pengajuan demo sedang diproses oleh Superadmin.
                        </div>
                    @elseif($feature->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($feature->demo_expires_at)))
                        <div class="alert alert-success py-2 small mb-2 text-center">
                            <i class="fa-solid fa-check-circle me-1"></i> Demo sedang aktif.
                        </div>
                    @elseif($feature->demo_used_count >= $feature->max_demo_requests)
                        <div class="alert alert-danger py-2 small mb-2 text-center">
                            <i class="fa-solid fa-ban me-1"></i> Batas penggunaan demo ({{ $feature->max_demo_requests }} kali) telah habis.
                        </div>
                    @else
                        @if($feature->demo_expires_at && \Carbon\Carbon::parse($feature->demo_expires_at)->isPast())
                            <div class="alert alert-warning py-2 small mb-2 text-center">
                                <i class="fa-solid fa-clock-rotate-left me-1"></i> Demo sebelumnya telah berakhir.
                            </div>
                        @endif
                        <form action="{{ route('premium.request_demo', $feature->menu_code) }}" method="POST" class="w-100 mb-2">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100 py-2 fw-bold text-dark" onclick="return confirm('Ajukan demo gratis ke Superadmin? Sisa kuota: {{ $feature->max_demo_requests - $feature->demo_used_count }} kali.')">
                                <i class="fa-solid fa-gift me-2"></i> Ajukan Demo Fitur (Tersisa: {{ $feature->max_demo_requests - $feature->demo_used_count }}x)
                            </button>
                        </form>
                    @endif
                @endif
                <a href="{{ url('/dashboard-admin') }}" class="btn btn-outline-secondary py-2 fw-bold">
                    <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
        <div class="card-footer bg-light text-center py-3 border-0">
            <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Setelah transfer, silakan hubungi pengembang untuk membuka kunci fitur ini.</small>
        </div>
    </div>

    @include('partials.sweetalerts')
</body>
</html>
