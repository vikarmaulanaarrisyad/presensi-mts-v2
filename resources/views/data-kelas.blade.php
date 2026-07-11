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

    <x-slot name="styles">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    </x-slot>

    <div class="container-fluid p-0">
        <div class="table-container-card">
            <h5 class="fw-bold text-dark mb-4">Daftar Kelas</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelKelas" style="width: 100%">
                    <thead class="table-light text-secondary" style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">
                        <tr>
                            <th scope="col" class="px-3 py-3" style="width: 50px;">No</th>
                            <th scope="col" class="py-3">Nama Kelas</th>
                            <th scope="col" class="py-3">ID Ruang</th>
                            <th scope="col" class="py-3">Wali Kelas</th>
                            <th scope="col" class="py-3 text-center">Kapasitas (Siswa)</th>
                            <th scope="col" class="py-3 text-center">Tingkat Kehadiran</th>
                            @if(session('user_role') === 'kepsek' || session('user_role') === 'admin')
                            <th scope="col" class="py-3 text-center" style="width: 100px;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.9rem; color: #475569;">
                        @foreach($kelases as $index => $kls)
                            @php
                                $persen = $kls->persentase_hadir ?? 0;
                                if ($persen >= 90) {
                                    $badgeClass = 'badge-premium-hadir';
                                    $icon = 'fa-circle-check';
                                } elseif ($persen >= 75) {
                                    $badgeClass = 'badge-premium-izin';
                                    $icon = 'fa-envelope';
                                } else {
                                    $badgeClass = 'badge-premium-absen';
                                    $icon = 'fa-circle-xmark';
                                }
                            @endphp
                            <tr>
                                <td class="px-3 fw-bold">{{ $index + 1 }}</td>
                                <td class="fw-bold text-dark">{{ $kls->nama_kelas }}</td>
                                <td class="font-mono-custom">{{ $kls->id_ruang }}</td>
                                <td>{{ $kls->wali_kelas }}</td>
                                <td class="text-center fw-bold">{{ $kls->jumlah_siswa }} / {{ $kls->kapasitas }}</td>
                                <td class="text-center">
                                    <span class="status-badge {{ $badgeClass }}">
                                        <i class="fa-solid {{ $icon }}"></i> {{ $persen }}%
                                    </span>
                                </td>
                                @if(session('user_role') === 'kepsek' || session('user_role') === 'admin')
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditKelas{{ $kls->id }}" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form action="{{ route('kelas.delete', $kls->id) }}" method="POST" onsubmit="return confirm('Peringatan: Menghapus kelas akan berdampak pada data siswa yang terhubung. Lanjutkan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabelKelas').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                },
                "pageLength": 10,
                "ordering": true
            });
        });
    </script>

        <!-- Display Validation Errors or Success Messages -->
@if($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                alert("{{ $errors->first() }}");
            });
        </script>
        @endif
    </x-slot>
    @include('partials.sweetalerts')
</x-layout-admin>
