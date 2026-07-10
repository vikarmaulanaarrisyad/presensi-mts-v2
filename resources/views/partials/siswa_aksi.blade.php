<div class="d-flex justify-content-center gap-2">
    <!-- Tombol Edit Siswa -->
    @php $fEdit = $premiumFeatures['aksi-edit'] ?? null; @endphp
    @if($fEdit && $fEdit->is_active && !$fEdit->is_unlocked && !($fEdit->has_demo && $fEdit->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($fEdit->demo_expires_at))))
        <button type="button" class="btn btn-sm btn-outline-primary px-2.5 py-1.5 rounded-3 font-mono-custom" title="Edit Siswa (Premium)" onclick="showPaywall('{{ $fEdit->nama_fitur }}', '{{ $fEdit->harga }}', '{{ $fEdit->has_demo }}', '{{ $fEdit->demo_requested }}', '{{ $fEdit->demo_expires_at }}', '{{ $fEdit->menu_code }}', '{{ $fEdit->max_demo_requests }}', '{{ $fEdit->demo_used_count }}', '{{ !empty($fEdit->payment_requested) ? 1 : 0 }}')"><i class="fa-solid fa-pencil"></i></button>
    @else
        <button type="button" class="btn btn-sm btn-outline-primary px-2.5 py-1.5 rounded-3 font-mono-custom" data-bs-toggle="modal" data-bs-target="#modalEditSiswa{{ $siswa->id }}" title="Edit Siswa"><i class="fa-solid fa-pencil"></i></button>
    @endif

    <!-- TOMBOL INPUT IZIN / SAKIT -->
    @php $fIzin = $premiumFeatures['aksi-izin'] ?? null; @endphp
    @if($fIzin && $fIzin->is_active && !$fIzin->is_unlocked && !($fIzin->has_demo && $fIzin->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($fIzin->demo_expires_at))))
        <button type="button" class="btn btn-sm btn-warning text-white px-2.5 py-1.5 rounded-3" title="Input Keterangan Absen (Premium)" onclick="showPaywall('{{ $fIzin->nama_fitur }}', '{{ $fIzin->harga }}', '{{ $fIzin->has_demo }}', '{{ $fIzin->demo_requested }}', '{{ $fIzin->demo_expires_at }}', '{{ $fIzin->menu_code }}', '{{ $fIzin->max_demo_requests }}', '{{ $fIzin->demo_used_count }}', '{{ !empty($fIzin->payment_requested) ? 1 : 0 }}')"><i class="fa-solid fa-envelope"></i></button>
    @else
        <button type="button" class="btn btn-sm btn-warning text-white px-2.5 py-1.5 rounded-3" data-bs-toggle="modal" data-bs-target="#modalIzin{{ $siswa->id }}" title="Input Keterangan Absen"><i class="fa-solid fa-envelope"></i></button>
    @endif

    <!-- TOMBOL WHATSAPP FONNTE -->
    @php $fWA = $premiumFeatures['aksi-whatsapp'] ?? null; @endphp
    @if($fWA && $fWA->is_active && !$fWA->is_unlocked && !($fWA->has_demo && $fWA->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($fWA->demo_expires_at))))
        <button type="button" class="btn btn-sm btn-outline-success px-2.5 py-1.5 rounded-3" title="Kirim WA Ketidakhadiran (Premium)" onclick="showPaywall('{{ $fWA->nama_fitur }}', '{{ $fWA->harga }}', '{{ $fWA->has_demo }}', '{{ $fWA->demo_requested }}', '{{ $fWA->demo_expires_at }}', '{{ $fWA->menu_code }}', '{{ $fWA->max_demo_requests }}', '{{ $fWA->demo_used_count }}', '{{ !empty($fWA->payment_requested) ? 1 : 0 }}')"><i class="fa-brands fa-whatsapp text-success fw-bold"></i></button>
    @else
        <a href="{{ route('siswa.notif_wa', $siswa->id) }}" class="btn btn-sm btn-outline-success px-2.5 py-1.5 rounded-3 btn-kirim-wa" data-name="{{ $siswa->name }}" title="Kirim WA Ketidakhadiran"><i class="fa-brands fa-whatsapp text-success fw-bold"></i></a>
    @endif

    @if(session('user_role') === 'admin' || session('user_role') === 'kepsek')
        <!-- TOMBOL REKAM/HAPUS JARI -->
        @php $fJari = $premiumFeatures['aksi-fingerprint'] ?? null; @endphp
        @if($fJari && $fJari->is_active && !$fJari->is_unlocked && !($fJari->has_demo && $fJari->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($fJari->demo_expires_at))))
            <button type="button" class="btn btn-sm btn-primary px-2.5 py-1.5 rounded-3" title="Rekam Jari di Alat (Premium)" onclick="showPaywall('{{ $fJari->nama_fitur }}', '{{ $fJari->harga }}', '{{ $fJari->has_demo }}', '{{ $fJari->demo_requested }}', '{{ $fJari->demo_expires_at }}', '{{ $fJari->menu_code }}', '{{ $fJari->max_demo_requests }}', '{{ $fJari->demo_used_count }}', '{{ !empty($fJari->payment_requested) ? 1 : 0 }}')"><i class="fa-solid fa-fingerprint"></i></button>
            @if($siswa->fingerprint_id)
                <button type="button" class="btn btn-sm btn-outline-secondary px-2.5 py-1.5 rounded-3" title="Reset Jari (Premium)" onclick="showPaywall('{{ $fJari->nama_fitur }}', '{{ $fJari->harga }}', '{{ $fJari->has_demo }}', '{{ $fJari->demo_requested }}', '{{ $fJari->demo_expires_at }}', '{{ $fJari->menu_code }}', '{{ $fJari->max_demo_requests }}', '{{ $fJari->demo_used_count }}', '{{ !empty($fJari->payment_requested) ? 1 : 0 }}')"><i class="fa-solid fa-rotate-left"></i></button>
                <button type="button" class="btn btn-sm btn-danger px-2.5 py-1.5 rounded-3" title="Hapus Jari dari Alat (Premium)" onclick="showPaywall('{{ $fJari->nama_fitur }}', '{{ $fJari->harga }}', '{{ $fJari->has_demo }}', '{{ $fJari->demo_requested }}', '{{ $fJari->demo_expires_at }}', '{{ $fJari->menu_code }}', '{{ $fJari->max_demo_requests }}', '{{ $fJari->demo_used_count }}', '{{ !empty($fJari->payment_requested) ? 1 : 0 }}')"><i class="fa-solid fa-eraser"></i></button>
            @endif
        @else
            @if(!$siswa->fingerprint_id)
                @if(array_key_exists($siswa->id, $activeEnrolls ?? []))
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-info text-white px-2.5 py-1.5" title="Sedang Menunggu Scan Jari di Alat..." disabled><i class="fa-solid fa-spinner fa-spin"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-danger px-2.5 py-1.5" title="Batalkan Pendaftaran" onclick="batalkanRekam({{ $activeEnrolls[$siswa->id] }})"><i class="fa-solid fa-xmark"></i></button>
                </div>
                @else
                <button type="button" class="btn btn-sm btn-primary px-2.5 py-1.5 rounded-3" data-bs-toggle="modal" data-bs-target="#modalRekam{{ $siswa->id }}" title="Rekam Jari di Alat"><i class="fa-solid fa-fingerprint"></i></button>
                @endif
            @else
                <form action="{{ route('siswa.reset_jari', $siswa->id) }}" method="POST" class="form-reset-jari" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary px-2.5 py-1.5 rounded-3" title="Reset Jari (Lokal DB)"><i class="fa-solid fa-rotate-left"></i></button>
                </form>
                <form action="{{ route('siswa.sync_jari', $siswa->id) }}" method="POST" class="form-sync-jari" data-name="{{ $siswa->name }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-info px-2.5 py-1.5 rounded-3" title="Sync Jari ke Semua Alat"><i class="fa-solid fa-cloud-arrow-down"></i></button>
                </form>
                <button type="button" class="btn btn-sm btn-danger px-2.5 py-1.5 rounded-3" data-bs-toggle="modal" data-bs-target="#modalHapusJari{{ $siswa->id }}" title="Hapus Jari dari Alat"><i class="fa-solid fa-eraser"></i></button>
            @endif
        @endif

        <!-- Tombol Hapus Siswa -->
        @php $fHapus = $premiumFeatures['aksi-hapus'] ?? null; @endphp
        @if($fHapus && $fHapus->is_active && !$fHapus->is_unlocked && !($fHapus->has_demo && $fHapus->demo_expires_at && \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($fHapus->demo_expires_at))))
            <button type="button" class="btn btn-sm btn-outline-danger px-2.5 py-1.5 rounded-3" title="Hapus Siswa (Premium)" onclick="showPaywall('{{ $fHapus->nama_fitur }}', '{{ $fHapus->harga }}', '{{ $fHapus->has_demo }}', '{{ $fHapus->demo_requested }}', '{{ $fHapus->demo_expires_at }}', '{{ $fHapus->menu_code }}', '{{ $fHapus->max_demo_requests }}', '{{ $fHapus->demo_used_count }}', '{{ !empty($fHapus->payment_requested) ? 1 : 0 }}')"><i class="fa-solid fa-trash-can"></i></button>
        @else
            <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" class="form-hapus-siswa" data-name="{{ $siswa->name }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1.5 rounded-3" title="Hapus Siswa"><i class="fa-solid fa-trash-can"></i></button>
            </form>
        @endif
    @endif
</div>
