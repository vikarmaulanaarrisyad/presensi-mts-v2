<x-layout-admin>
    <x-slot name="title">Data Guru</x-slot>
    <x-slot name="headerTitle">Data Guru</x-slot>
    <x-slot name="headerSubtitle">Manajemen Data Pengajar dan Hak Akses Sistem</x-slot>

    <x-slot name="headerActions">
            @if(session('user_role') === 'admin' || session('user_role') === 'superadmin')
                    <button class="btn btn-success fw-bold px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" style="font-size: 0.88rem; background-color: var(--accent-green); border:none;" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="fa-solid fa-user-plus fs-5"></i> Tambah Akun Baru
                    </button>
                    @endif
    </x-slot>

    <x-slot name="styles">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    </x-slot>

    <div class="table-container-card">
                <div class="table-responsive">
                    <table class="table custom-table table-hover align-middle mb-0" id="tabelGuru" style="width: 100%">
                        <thead style="background-color: #f8fafc;">
                            <tr>
                                <th class="text-secondary fw-bold text-uppercase" style="font-size:0.75rem; border-bottom: 2px solid #e2e8f0;">No</th>
                                <th class="text-secondary fw-bold text-uppercase" style="font-size:0.75rem; border-bottom: 2px solid #e2e8f0;">Nama Lengkap</th>
                                <th class="text-secondary fw-bold text-uppercase" style="font-size:0.75rem; border-bottom: 2px solid #e2e8f0;">Email / Username</th>
                                <th class="text-secondary fw-bold text-uppercase" style="font-size:0.75rem; border-bottom: 2px solid #e2e8f0;">Role Jabatan</th>
                                <th class="text-secondary fw-bold text-uppercase text-end" style="font-size:0.75rem; border-bottom: 2px solid #e2e8f0;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gurus as $index => $guru)
                            <tr>
                                <td class="font-mono-custom text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $guru->name }}</div>
                                </td>
                                <td class="text-muted font-mono-custom" style="font-size: 0.85rem;">{{ $guru->email }}</td>
                                <td>
                                    @if($guru->role == 'kepsek')
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 font-mono-custom fw-bold" style="font-size:0.75rem;"><i class="fa-solid fa-crown me-1"></i> Kepala Sekolah</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 font-mono-custom fw-bold" style="font-size:0.75rem;"><i class="fa-solid fa-chalkboard-user me-1"></i> Guru</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $guru->id }}">
                                        <i class="fa-solid fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold ms-1" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $guru->id }}">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit{{ $guru->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content modal-style-content">
                                        <div class="modal-header modal-style-header bg-primary text-white">
                                            <h5 class="modal-title fw-bold" style="font-size:1.05rem;"><i class="fa-solid fa-edit me-2"></i>Edit Data Guru</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <form action="{{ route('guru.update', $guru->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                                                    <input type="text" name="name" class="form-control py-2.5" value="{{ $guru->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Email / Username</label>
                                                    <input type="email" name="email" class="form-control py-2.5" value="{{ $guru->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Role Jabatan</label>
                                                    <select name="role" class="form-select py-2.5" required>
                                                        <option value="guru" {{ $guru->role == 'guru' ? 'selected' : '' }}>Guru</option>
                                                        <option value="kepsek" {{ $guru->role == 'kepsek' ? 'selected' : '' }}>Kepala Sekolah</option>
                                                    </select>
                                                </div>
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Ganti Password (Opsional)</label>
                                                    <input type="password" name="password" class="form-control py-2.5" placeholder="Kosongkan jika tidak ingin diubah">
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100 fw-bold py-2.5 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Hapus -->
                            <div class="modal fade" id="modalHapus{{ $guru->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content modal-style-content" style="text-align: center;">
                                        <div class="modal-body p-4">
                                            <div class="mb-3 text-danger">
                                                <i class="fa-solid fa-circle-exclamation fa-3x"></i>
                                            </div>
                                            <h5 class="fw-bold mb-3">Hapus Akun?</h5>
                                            <p class="text-muted small mb-4">Apakah Anda yakin ingin menghapus akun <strong>{{ $guru->name }}</strong>? Data tidak bisa dikembalikan.</p>
                                            <form action="{{ route('guru.delete', $guru->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-light w-50 fw-bold rounded-pill" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger w-50 fw-bold rounded-pill">Ya, Hapus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-users-slash fa-3x mb-3 opacity-25"></i>
                                    <p class="mb-0 fw-bold">Belum Ada Data Guru</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Tambah -->
        <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-style-content">
                    <div class="modal-header modal-style-header bg-success text-white">
                        <h5 class="modal-title fw-bold" style="font-size:1.05rem;"><i class="fa-solid fa-user-plus me-2"></i>Tambah Akun Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-info border-0 rounded-3 small fw-bold mb-4">
                            <i class="fa-solid fa-info-circle me-1"></i> Password default akun baru adalah: <strong>12345678</strong>
                        </div>
                        <form action="{{ route('guru.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control py-2.5" placeholder="Nama Guru / Kepsek" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Email / Username</label>
                                <input type="email" name="email" class="form-control py-2.5" placeholder="email@sekolah.com" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Role Jabatan</label>
                                <select name="role" class="form-select py-2.5" required>
                                    <option value="guru" selected>Guru</option>
                                    <option value="kepsek">Kepala Sekolah</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2.5 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2"><i class="fa-solid fa-save"></i> Simpan Data</button>
                        </form>
                    </div>
                </div>
            </div>

    <x-slot name="scripts">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabelGuru').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                },
                "pageLength": 10,
                "ordering": true
            });
        });
    </script>
    </x-slot>
    @include('partials.sweetalerts')
</x-layout-admin>