<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi MTs Mambaul Ulum Kota Tegal</title>
    <style>
        body { font-family: 'Arial', sans-serif; color: #333; line-height: 1.4; padding: 20px; }
        .kop-surat { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h2 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .kop-surat h3 { margin: 5px 0; font-size: 16px; text-transform: uppercase; }
        .kop-surat p { margin: 0; font-size: 12px; font-style: italic; }
        .judul-laporan { text-align: center; margin-bottom: 20px; text-transform: uppercase; font-size: 14px; font-weight: bold; }
        .sub-judul-laporan { font-size: 11px; color: #555; text-transform: none; font-weight: normal; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 12px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .badge-izin { color: #28a745; font-weight: bold; }
        .badge-hadir { color: #007bff; font-weight: bold; }
        .badge-absen { color: #dc3545; font-weight: bold; }
        
        /* Trigger otomatis save PDF saat halaman dibuka */
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <table class="kop-surat" style="border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; width: 100%; border: none;">
        <tr>
            <td style="width: 15%; text-align: center; border: none;">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo MTs" style="width: 80px; height: auto;">
                @endif
            </td>
            <td style="width: 70%; text-align: center; border: none;">
                <h2 style="margin: 0; font-size: 20px; text-transform: uppercase;">Yayasan Mambaul Ulum Tegal</h2>
                <h3 style="margin: 5px 0; font-size: 16px; text-transform: uppercase;">MTs Mambaul Ulum Kota Tegal</h3>
                <p style="margin: 0; font-size: 12px; font-style: italic;">Alamat: Jl. Nyi Ageng Serang, Tunon, Kec. Tegal Selatan, Kota Tegal 52135</p>
            </td>
            <td style="width: 15%; border: none;"></td>
        </tr>
    </table>
    <div class="judul-laporan">
        Laporan Rekapitulasi Absensi Kehadiran Siswa<br>
        Sistem Sinkronisasi Cloud & Rekap Otomatis
        <div class="sub-judul-laporan">
            Periode: <strong>{{ $periodeFormat ?? 'Periode Ini' }}</strong> | Kelas: <strong>{{ $kelas ?? 'Semua Kelas' }}</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">NIS</th>
                <th width="20%">Nama Siswa</th>
                <th width="10%">Kelas</th>
                <th width="15%">Status Kehadiran</th>
                <th width="20%">Keterangan / Alasan</th>
                <th width="20%">Waktu Penginputan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataAbsensi as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $row->nis ?? '-' }}</td>
                <td><strong>{{ $row->nama_siswa ?? '-' }}</strong></td>
                <td class="text-center">{{ $row->kelas ?? '-' }}</td>
                <td class="text-center">
                    @if($row->status_masuk == 'Izin')
                        <span class="badge-izin">Izin</span>
                    @elseif($row->status_masuk == 'Sakit')
                        <span class="badge-izin">Sakit</span>
                    @elseif($row->status_masuk == 'Hadir')
                        <span class="badge-hadir">Hadir</span>
                    @else
                        <span class="badge-absen">Alpa</span>
                    @endif
                </td>
                <td>{{ $row->keterangan_masuk ?? '-' }}</td>
                <td class="text-center">
                    {{ $row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->format('d-m-Y H:i') . ' WIB' : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px; color: #666;">Belum ada data rekapan absensi cloud yang terekam pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table style="width: 100%; border: none; margin-top: 30px;">
        <tr>
            <td style="width: 60%; border: none;"></td>
            <td style="width: 40%; text-align: center; border: none;">
                <p style="margin-bottom: 60px; font-size: 12px;">Tegal, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY') }}<br>Tenaga Usaha (TU)</p>
                <p style="font-weight: bold; text-decoration: underline; font-size: 12px;">{{ $namaAdminTU }}</p>
            </td>
        </tr>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
    @include('partials.sweetalerts')
</body>
</html>
