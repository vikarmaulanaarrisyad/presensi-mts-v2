<x-layout-admin>
    <x-slot name="title">Data Induk Siswa</x-slot>
    <x-slot name="headerTitle">Data Induk Siswa</x-slot>
    <x-slot name="headerSubtitle">Manajemen Biodata dan Registrasi Biometrik Fingerprint Terintegrasi</x-slot>

    <x-slot name="headerActions">
            @if(session('user_role') === 'admin' || session('user_role') === 'kepsek')
                    <button class="btn btn-success fw-bold px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" style="font-size: 0.88rem; background-color: var(--accent-green); border:none;" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
                        <i class="fa-solid fa-user-plus fs-5"></i> Tambah Siswa
                    </button>
                    @endif
            @if(session('user_role') === 'admin')
                    <button id="btnResetSemuaJari" class="btn btn-danger fw-bold px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" style="font-size: 0.88rem; border:none;" onclick="konfirmasiResetSemuaJari()">
                        <i class="fa-solid fa-fingerprint fs-5"></i> Reset Semua Jari
                    </button>
            @endif
    </x-slot>

    <x-slot name="styles">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    </x-slot>

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
                    <table id="tabelSiswa" class="table table-hover align-middle mb-0" style="width: 100%">
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
        </div>

        <!-- SEMUA MODAL SISWA BERADA DI LUAR TABLE -->
        @if(isset($siswas) && count($siswas) > 0)
            @foreach($siswas as $siswa)
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
                                                <option value="{{ $d->id }}" data-token="{{ $d->device_token }}">{{ $d->nama_alat }} - {{ $d->ip_address }}</option>
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
                                <form id="form-hapus-jari-{{ $siswa->id }}" action="{{ route('siswa.hapus_jari_alat') }}" method="POST" data-name="{{ $siswa->name }}">
                                    @csrf
                                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Pilih Alat Fingerprint</label>
                                        <select name="device_id" class="form-select py-2.5" style="border-radius:8px;" required>
                                            <option value="">-- Pilih Alat --</option>
                                            @foreach($devices as $d)
                                                <option value="{{ $d->id }}" data-token="{{ $d->device_token }}">{{ $d->nama_alat }} - {{ $d->ip_address }}</option>
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
        @endif

    <x-slot name="scripts">
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
                } else if (form && form.id && form.id.startsWith('form-hapus-jari-')) {
                    event.preventDefault();
                    const siswaId = form.id.replace('form-hapus-jari-', '');
                    handleHapusJariAction(form, siswaId);
                }
            });

            function handleRekamJariAction(form, siswaId) {
                try {
                    const formData = new FormData(form);
                    const deviceId = formData.get('device_id');
                    const studentName = form.getAttribute('data-name');
                    
                    const selectEl = form.querySelector('select[name="device_id"]');
                    const selectedOption = selectEl.options[selectEl.selectedIndex];
                    const deviceToken = selectedOption.getAttribute('data-token');

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
                        // Polling dihapus sesuai permintaan.
                        // Menampilkan pesan sukses terkirim ke alat.
                        loadingContainer.innerHTML = `
                            <div class="text-center py-5">
                                <i class="fa-solid fa-paper-plane fa-bounce fa-4x text-primary mb-4"></i>
                                <h5 class="fw-bold">Perintah Terkirim!</h5>
                                <p class="text-muted small mb-3">Silakan lihat layar alat ESP32 dan tempelkan jari.<br>Setelah sukses di alat, data akan otomatis direfresh.</p>
                            </div>
                        `;
                        
                        if (window.listenFirebaseEnroll) {
                            window.listenFirebaseEnroll(deviceToken, siswaId, 'modalRekam' + siswaId);
                        }
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

            function handleHapusJariAction(form, siswaId) {
                try {
                    const formData = new FormData(form);
                    const deviceId = formData.get('device_id');
                    const studentName = form.getAttribute('data-name');
                    
                    const selectEl = form.querySelector('select[name="device_id"]');
                    const selectedOption = selectEl.options[selectEl.selectedIndex];
                    const deviceToken = selectedOption.getAttribute('data-token');

                    // Sembunyikan form secara paksa
                    form.style.display = 'none';
                    form.classList.add('d-none');

                    // Buat div loading jika belum ada
                    let loadingContainer = document.getElementById('loading-hapus-container-' + siswaId);
                    if (!loadingContainer) {
                        loadingContainer = document.createElement('div');
                        loadingContainer.id = 'loading-hapus-container-' + siswaId;
                        form.parentElement.appendChild(loadingContainer);
                    }

                    loadingContainer.classList.remove('d-none');
                    loadingContainer.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fa-solid fa-spinner fa-spin-pulse fa-4x text-danger mb-4"></i>
                            <h5 class="fw-bold">Sedang Menghapus Jari...</h5>
                            <p class="text-danger fw-bold fs-5 mb-2">${studentName}</p>
                            <p class="text-muted small mb-4">Mohon tunggu sementara alat memproses permintaan.<br>Jangan tutup jendela ini.</p>
                            <button type="button" class="btn btn-outline-secondary btn-sm px-4 rounded-3 fw-bold" onclick="batalkanRekam(${deviceId}, ${siswaId}, this)">
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
                        loadingContainer.innerHTML = `
                            <div class="text-center py-5">
                                <i class="fa-solid fa-paper-plane fa-bounce fa-4x text-danger mb-4"></i>
                                <h5 class="fw-bold">Perintah Terkirim!</h5>
                                <p class="text-muted small mb-3">Sistem sedang memerintahkan alat untuk menghapus sidik jari.<br>Mohon tunggu sebentar.</p>
                            </div>
                        `;
                        
                        if (window.listenFirebaseDelete) {
                            window.listenFirebaseDelete(deviceToken, siswaId, 'modalHapusJari' + siswaId);
                        }
                    }).catch(err => {
                        loadingContainer.innerHTML = `
                            <div class="text-center py-5">
                                <i class="fa-solid fa-triangle-exclamation fa-4x text-warning mb-4"></i>
                                <h5 class="fw-bold">Gagal Diproses!</h5>
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

            // Fitur auto-refresh tabel telah dihapus sesuai permintaan.
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabelSiswa').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('api.data.siswa') }}',
                    error: function (xhr, error, code) {
                        console.error('DataTables Ajax Error:', error, code, xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memuat Data',
                            text: 'Gagal mengambil data dari server. Silakan cek console untuk detailnya (F12).'
                        });
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'nis', name: 'nis' },
                    { data: 'kelas', name: 'kelas' },
                    { data: 'fingerprint_id', name: 'fingerprint_id' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' }
                ]
            });

            // SweetAlert2 event delegation
            $('#tabelSiswa').on('click', '.btn-kirim-wa', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                let nama = $(this).data('name');
                Swal.fire({
                    title: 'Kirim Pesan WhatsApp?',
                    text: "Kirim notifikasi ketidakhadiran ke orang tua " + nama + "?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'GET',
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            success: function(response) {
                                if(response.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Terkirim!',
                                        text: response.message,
                                        confirmButtonColor: '#10b981',
                                        timer: 3000
                                    });
                                } else if (response.status === 'warning') {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Peringatan!',
                                        text: response.message,
                                        confirmButtonColor: '#f59e0b'
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!', xhr.responseJSON?.message || 'Terjadi kesalahan sistem.', 'error');
                            }
                        });
                    }
                });
            });

            $('#tabelSiswa').on('submit', '.form-reset-jari', function(e) {
                e.preventDefault();
                let form = this;
                Swal.fire({
                    title: 'Reset Sidik Jari?',
                    text: "Yakin ingin mereset/menghapus ikatan sidik jari siswa ini dari database lokal?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Reset!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.action,
                            type: form.method || 'POST',
                            data: $(form).serialize(),
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            success: function(response) {
                                if(response.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        confirmButtonColor: '#10b981',
                                        timer: 3000
                                    });
                                    $('#tabelSiswa').DataTable().ajax.reload(null, false);
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                            }
                        });
                    }
                });
            });

            $('#tabelSiswa').on('submit', '.form-hapus-siswa', function(e) {
                e.preventDefault();
                let form = this;
                let nama = $(this).data('name');
                Swal.fire({
                    title: 'Hapus Data Siswa?',
                    text: "Apakah Anda yakin ingin menghapus data siswa bernama " + nama + "? Hubungan data fingerprint di mesin juga akan ikut terhapus.",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.action,
                            type: $(form).find('input[name="_method"]').val() || form.method || 'POST',
                            data: $(form).serialize(),
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            success: function(response) {
                                if(response.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        confirmButtonColor: '#10b981',
                                        timer: 3000
                                    });
                                    $('#tabelSiswa').DataTable().ajax.reload(null, false);
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                            }
                        });
                    }
                });
            });
            $('#tabelSiswa').on('submit', '.form-sync-jari', function(e) {
                e.preventDefault();
                let form = this;
                let nama = $(this).data('name');
                Swal.fire({
                    title: 'Sinkronisasi Jari?',
                    text: "Apakah Anda yakin ingin menyinkronkan (menyebarkan) pola sidik jari milik " + nama + " ke seluruh alat absensi?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0dcaf0',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Sinkronkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.action,
                            type: form.method || 'POST',
                            data: $(form).serialize(),
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            success: function(response) {
                                if(response.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Tersinkronisasi!',
                                        text: response.message,
                                        confirmButtonColor: '#10b981',
                                        timer: 3000
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!', xhr.responseJSON?.message || 'Terjadi kesalahan sinkronisasi.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        function konfirmasiResetSemuaJari() {
            Swal.fire({
                title: '⚠️ Reset Semua Sidik Jari?',
                html: `<div class="text-start">
                    <p class="mb-2">Tindakan ini akan:</p>
                    <ul class="small text-danger fw-bold">
                        <li>Menghapus semua data fingerprint dari <strong>database</strong></li>
                        <li>Mengirim perintah hapus ke <strong>semua alat ESP32</strong></li>
                        <li>Semua siswa harus <strong>mendaftar ulang sidik jari</strong></li>
                    </ul>
                    <p class="mt-2 text-muted small">Tindakan ini <strong>tidak dapat dibatalkan</strong>.</p>
                    <p class="mt-2 fw-bold">Ketik <code>RESET</code> untuk konfirmasi:</p>
                    <input type="text" id="konfirmasi-reset-input" class="form-control mt-1" placeholder="Ketik RESET di sini">
                </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa-solid fa-fingerprint me-1"></i> Ya, Reset Semua!',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const val = document.getElementById('konfirmasi-reset-input').value;
                    if (val !== 'RESET') {
                        Swal.showValidationMessage('Ketik RESET (huruf kapital) untuk melanjutkan!');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const btn = document.getElementById('btnResetSemuaJari');
                    const originalHtml = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Memproses...';

                    $.ajax({
                        url: '{{ route("siswa.reset_semua_jari") }}',
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            btn.disabled = false;
                            btn.innerHTML = originalHtml;

                            const icon = response.status === 'success' ? 'success' : 'warning';
                            Swal.fire({
                                icon: icon,
                                title: response.status === 'success' ? 'Berhasil Direset!' : 'Perhatian',
                                text: response.message,
                                confirmButtonColor: '#10b981'
                            }).then(() => {
                                $('#tabelSiswa').DataTable().ajax.reload(null, false);
                            });
                        },
                        error: function(xhr) {
                            btn.disabled = false;
                            btn.innerHTML = originalHtml;
                            Swal.fire('Gagal!', xhr.responseJSON?.message || 'Terjadi kesalahan sistem.', 'error');
                        }
                    });
                }
            });
        }
    </script>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
        import { getDatabase, ref, onValue, off, remove } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-database.js";

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

        window.listenFirebaseEnroll = function(deviceToken, siswaId, modalId) {
            const dbRef = ref(database, 'enroll_responses/' + deviceToken);
            onValue(dbRef, (snapshot) => {
                const data = snapshot.val();
                if (data && data.status === 'success' && parseInt(data.siswa_id) === parseInt(siswaId)) {
                    off(dbRef); 
                    remove(dbRef); 
                    
                    let modalEl = document.getElementById(modalId);
                    if (modalEl) {
                        let modalIns = bootstrap.Modal.getInstance(modalEl);
                        if (modalIns) {
                            modalIns.hide();
                            // Hapus backdrop jika tertinggal
                            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                            document.body.classList.remove('modal-open');
                        }
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Rekam Jari Berhasil!',
                        text: 'Sidik jari siswa telah sukses direkam dan tersimpan.',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        $('#tabelSiswa').DataTable().ajax.reload(null, false);
                    });
                }
            });
        };

        window.listenFirebaseDelete = function(deviceToken, siswaId, modalId) {
            const dbRef = ref(database, 'delete_responses/' + deviceToken);
            onValue(dbRef, (snapshot) => {
                const data = snapshot.val();
                if (data && data.status === 'success' && parseInt(data.siswa_id) === parseInt(siswaId)) {
                    off(dbRef); 
                    remove(dbRef); 
                    
                    let modalEl = document.getElementById(modalId);
                    if (modalEl) {
                        let modalIns = bootstrap.Modal.getInstance(modalEl);
                        if (modalIns) {
                            modalIns.hide();
                            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                            document.body.classList.remove('modal-open');
                        }
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Hapus Jari Berhasil!',
                        text: 'Data sidik jari telah dihapus dari alat dan server.',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        $('#tabelSiswa').DataTable().ajax.reload(null, false);
                    });
                }
            });
        };

        // ===== LISTENER RESET SEMUA JARI =====
        // Mendengarkan notifikasi ketika ESP32 selesai menghapus semua jari (delete_all)
        // Dibuat per device yang terdaftar
        @foreach(\App\Models\Device::all() as $dev)
        (function() {
            const resetRef = ref(database, 'reset_all_responses/{{ $dev->device_token }}');
            let resetInitialLoad = true;
            onValue(resetRef, (snapshot) => {
                if (resetInitialLoad) { resetInitialLoad = false; return; }
                const data = snapshot.val();
                if (!data) return;
                off(resetRef);
                remove(resetRef);

                const icon   = data.status === 'success' ? 'success' : 'warning';
                const title  = data.status === 'success' ? 'Reset Selesai!' : 'Reset Selesai (Sebagian)';
                const text   = `Alat: {{ $dev->nama_alat }}\nBerhasil: ${data.total_dihapus ?? 0} jari\nGagal: ${data.total_gagal ?? 0} jari`;

                Swal.fire({ icon, title, text, confirmButtonColor: '#10b981' }).then(() => {
                    if (window.$ && $('#tabelSiswa').length) {
                        $('#tabelSiswa').DataTable().ajax.reload(null, false);
                    }
                });
            });
        })();
        @endforeach
    </script>
    </x-slot>
    @include('partials.sweetalerts')
</x-layout-admin>