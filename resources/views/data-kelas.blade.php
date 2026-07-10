<x-layout-admin>
    <x-slot name="title">Data Kelas</x-slot>
    <x-slot name="headerTitle">Data Kelas</x-slot>
    <x-slot name="headerSubtitle">Manajemen Rombongan Belajar dan Penugasan Wali Kelas</x-slot>

    <x-slot name="headerActions">
            @if(session('user_role') === 'kepsek' || session('user_role') === 'admin')
                    <button class="btn btn-success fw-bold px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" style="font-size: 0.88rem; background-color: var(--accent-green); border:none;" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
                        <i class="fa-solid fa-plus-circle fs-5"></i> Tambah Kelas
                    </button>
                    @endif
    </x-slot>

    @php
                    $persen = $kls->persentase_hadir ?? 0;
                    if ($persen >= 90) {
                        $badgeClass = 'bg-success bg-opacity-10 text-success';
                        $progressClass = 'bg-success';
                    } elseif ($persen >= 75) {
                        $badgeClass = 'bg-warning bg-opacity-10 text-warning';
                        $progressClass = 'bg-warning';
                    } else {
                        $badgeClass = 'bg-danger bg-opacity-10 text-danger';
                        $progressClass = 'bg-danger';
                    }
                @endphp
                <div class="col-md-4">
                    <div class="class-box-card">
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h4 class="fw-bold text-dark mb-0" style="font-size: 1.25rem;">
                                        Kelas {{ $kls->nama_kelas }}
                                    </h4>
                                    <span class="text-muted font-mono-custom text-uppercase" style="font-size:0.75rem;">
                                        ID: {{ $kls->id_ruang }}
                                    </span>
                                </div>
                                <span class="badge {{ $badgeClass }} rounded-pill px-2 py-1 font-mono-custom fw-bold" style="font-size:0.75rem;">
                                    {{ $persen }}% Hadir
                                </span>
                            </div>
                            <hr class="text-muted my-3 opacity-25">
                            <div class="small mb-3">
                                <div class="d-flex justify-content-between text-muted mb-1">
                                    <span>Kapasitas Terisi:</span>
                                    <span class="fw-bold text-dark">{{ $kls->jumlah_siswa }} / {{ $kls->kapasitas }} Siswa</span>
                                </div>
                                <div class="d-flex justify-content-between text-muted mb-1">
                                    <span>Wali Kelas:</span>
                                    <span class="fw-bold text-dark">{{ $kls->wali_kelas }}</span>
                                </div>
                            </div>

                            @if(session('user_role') === 'kepsek' || session('user_role') === 'admin')
                            <div class="d-flex gap-2 mt-3 pt-3 border-top">
                                <button type="button" class="btn btn-sm btn-outline-primary flex-grow-1 fw-bold" style="font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#modalEditKelas{{ $kls->id }}">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                <form action="{{ route('kelas.delete', $kls->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Peringatan: Menghapus kelas akan berdampak pada data siswa yang terhubung. Lanjutkan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 fw-bold" style="font-size: 0.8rem;">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center text-muted py-5">
                        <i class="fa-solid fa-school fa-2x mb-2"></i>
                        <p>Belum ada data kelas yang terdaftar.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- MODAL TAMBAH KELAS -->
        <div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-labelledby="modalTambahKelasLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-style-content">
                    <div class="modal-header modal-style-header bg-success text-white">
                        <h5 class="modal-title fw-bold" id="modalTambahKelasLabel" style="font-size:1.05rem;"><i class="fa-solid fa-plus-circle me-2"></i>Tambah Kelas Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form action="{{ route('kelas.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Kelas</label>
                                <input type="text" name="nama_kelas" class="form-control py-2.5" placeholder="Contoh: VII - A" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Wali Kelas</label>
                                <input type="text" name="wali_kelas" class="form-control py-2.5" placeholder="Nama lengkap wali kelas" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">ID Ruang</label>
                                    <input type="text" name="id_ruang" class="form-control py-2.5" placeholder="Contoh: R-01" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Kapasitas</label>
                                    <input type="number" name="kapasitas" class="form-control py-2.5" placeholder="Maks siswa" value="32" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2.5 text-white rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <i class="fa-solid fa-save"></i> Simpan Data Kelas
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT KELAS -->
        @foreach($kelases as $kls)
        <div class="modal fade" id="modalEditKelas{{ $kls->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-style-content">
                    <div class="modal-header modal-style-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" style="font-size:1.05rem;"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Kelas</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form action="{{ route('kelas.update', $kls->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Kelas</label>
                                <input type="text" name="nama_kelas" class="form-control py-2.5" value="{{ $kls->nama_kelas }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Wali Kelas</label>
                                <input type="text" name="wali_kelas" class="form-control py-2.5" value="{{ $kls->wali_kelas }}" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">ID Ruang</label>
                                    <input type="text" name="id_ruang" class="form-control py-2.5" value="{{ $kls->id_ruang }}" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Kapasitas</label>
                                    <input type="number" name="kapasitas" class="form-control py-2.5" value="{{ $kls->kapasitas }}" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2.5 text-white rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <i class="fa-solid fa-save"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Bootstrap JS (required for modals) -->

    <x-slot name="scripts">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Display Validation Errors or Success Messages -->
        @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                alert("{{ session('success') }}");
            });
        </script>
        @endif
        @if($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                alert("{{ $errors->first() }}");
            });
        </script>
        @endif
    </x-slot>
</x-layout-admin>