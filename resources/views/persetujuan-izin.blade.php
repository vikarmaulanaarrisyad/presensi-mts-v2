<x-layout-admin>
    <x-slot name="title">Persetujuan Izin</x-slot>
    <x-slot name="headerTitle">Persetujuan Izin & Alpa</x-slot>
    <x-slot name="headerSubtitle">Kelola pengajuan izin atau sakit dari siswa yang menunggu persetujuan</x-slot>

    <x-slot name="styles">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    </x-slot>

    <div class="container-fluid p-0">
        <div class="table-container-card">
            <h5 class="fw-bold text-dark mb-4"><i class="fa-solid fa-clipboard-list me-2"></i>Daftar Pengajuan Pending</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelPersetujuan" style="width: 100%">
                    <thead class="table-light text-secondary" style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">
                        <tr>
                            <th scope="col" class="px-3 py-3" style="width: 50px;">No</th>
                            <th scope="col" class="py-3">Tanggal</th>
                            <th scope="col" class="py-3">Siswa</th>
                            <th scope="col" class="py-3">Pengajuan</th>
                            <th scope="col" class="py-3">Keterangan</th>
                            <th scope="col" class="py-3 text-center" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.9rem; color: #475569;">
                        @forelse($pengajuanPending as $index => $pengajuan)
                            <tr>
                                <td class="px-3 fw-bold">{{ $index + 1 }}</td>
                                <td class="fw-bold text-dark">{{ \Carbon\Carbon::parse($pengajuan->tanggal)->translatedFormat('d F Y') }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $pengajuan->nama_siswa }}</div>
                                    <div class="small text-muted">{{ $pengajuan->kelas ?? '-' }} | NIS: {{ $pengajuan->nis ?? '-' }}</div>
                                </td>
                                <td>
                                    @if($pengajuan->status_masuk === 'Izin')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1"><i class="fa-solid fa-envelope me-1"></i> Izin</span>
                                    @elseif($pengajuan->status_masuk === 'Sakit')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1"><i class="fa-solid fa-briefcase-medical me-1"></i> Sakit</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2 py-1">{{ $pengajuan->status_masuk }}</span>
                                    @endif
                                </td>
                                <td>{{ $pengajuan->keterangan_masuk }}</td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <form action="{{ route('persetujuan.izin.approve', $pengajuan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI pengajuan ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success fw-bold" title="Setujui">
                                                <i class="fa-solid fa-check"></i> Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('persetujuan.izin.reject', $pengajuan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK pengajuan ini? Status akan diubah menjadi Alpa.');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger fw-bold" title="Tolak">
                                                <i class="fa-solid fa-xmark"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <!-- DataTables akan meng-handle pesan kosong secara otomatis -->
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#tabelPersetujuan').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
                        "emptyTable": "<div class='text-center py-4 text-muted'><i class='fa-solid fa-check-circle fs-3 mb-2 text-success opacity-50'></i><br>Tidak ada pengajuan yang perlu disetujui saat ini.</div>"
                    },
                    "pageLength": 10,
                    "ordering": true
                });
            });
        </script>
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
