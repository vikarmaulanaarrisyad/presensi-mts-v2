<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Presensi MTS - Premium Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="h-full gradient-bg">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-7xl">
            <!-- Header -->
            <div class="text-center mb-8 animate-fade-in">
                <div class="glass rounded-2xl p-8 mb-6">
                    <div class="flex items-center justify-center mb-4">
                        <i class="fas fa-school text-4xl text-white mr-4"></i>
                        <h1 class="text-4xl md:text-5xl font-bold text-white">MONITORING PRESENSI</h1>
                    </div>
                    <h2 class="text-xl md:text-2xl text-white/90 font-light">Madrasah Tsanawiyah</h2>
                    <p class="text-white/80 mt-2">Sistem Monitoring Kehadiran Siswa Real-Time</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-slide-up">
                <div class="glass rounded-xl p-6 card-hover">
                    <div class="flex items-center">
                        <div class="bg-green-500 rounded-full p-3 mr-4">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-white/80 text-sm">Total Siswa</p>
                            <p class="text-white text-2xl font-bold">{{ $attendances->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="glass rounded-xl p-6 card-hover">
                    <div class="flex items-center">
                        <div class="bg-blue-500 rounded-full p-3 mr-4">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-white/80 text-sm">Hadir Hari Ini</p>
                            <p class="text-white text-2xl font-bold">{{ $attendances->where('status', 'Hadir')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="glass rounded-xl p-6 card-hover">
                    <div class="flex items-center">
                        <div class="bg-yellow-500 rounded-full p-3 mr-4">
                            <i class="fas fa-calendar-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-white/80 text-sm">Tanggal</p>
                            <p class="text-white text-lg font-bold">{{ date('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="glass rounded-xl p-6 card-hover">
                    <div class="flex items-center">
                        <div class="bg-purple-500 rounded-full p-3 mr-4">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-white/80 text-sm">Persentase</p>
                            <p class="text-white text-2xl font-bold">{{ $attendances->count() > 0 ? round(($attendances->where('status', 'Hadir')->count() / $attendances->count()) * 100) : 0 }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Data Table -->
            <div class="glass rounded-2xl p-8 animate-slide-up">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-table text-xl mr-3"></i>
                        Data Presensi Hari Ini
                    </h3>
                    <div class="text-white/80">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Update Terakhir: {{ date('H:i:s') }}
                    </div>
                </div>

                @if($attendances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-white">
                        <thead>
                            <tr class="border-b border-white/20">
                                <th class="text-left py-4 px-4 font-semibold">
                                    <i class="fas fa-user mr-2"></i>Nama Siswa
                                </th>
                                <th class="text-left py-4 px-4 font-semibold">
                                    <i class="fas fa-graduation-cap mr-2"></i>Kelas
                                </th>
                                <th class="text-left py-4 px-4 font-semibold">
                                    <i class="fas fa-clock mr-2"></i>Waktu Scan
                                </th>
                                <th class="text-left py-4 px-4 font-semibold">
                                    <i class="fas fa-book mr-2"></i>Pelajaran
                                </th>
                                <th class="text-center py-4 px-4 font-semibold">
                                    <i class="fas fa-check-circle mr-2"></i>Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $row)
                            <tr class="border-b border-white/10 hover:bg-white/5 transition-colors duration-200">
                                <td class="py-4 px-4 font-medium">{{ $row->student->nama }}</td>
                                <td class="py-4 px-4">{{ $row->student->kelas }}</td>
                                <td class="py-4 px-4">{{ $row->waktu_scan }}</td>
                                <td class="py-4 px-4">{{ $row->pelajaran }}</td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($row->status == 'Hadir') bg-green-500/20 text-green-300 border border-green-500/30
                                        @elseif($row->status == 'Terlambat') bg-yellow-500/20 text-yellow-300 border border-yellow-500/30
                                        @else bg-red-500/20 text-red-300 border border-red-500/30
                                        @endif">
                                        @if($row->status == 'Hadir')
                                            <i class="fas fa-check-circle mr-2"></i>
                                        @elseif($row->status == 'Terlambat')
                                            <i class="fas fa-clock mr-2"></i>
                                        @else
                                            <i class="fas fa-times-circle mr-2"></i>
                                        @endif
                                        {{ $row->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-6xl text-white/30 mb-4"></i>
                    <h4 class="text-xl text-white/80 mb-2">Belum Ada Data Presensi</h4>
                    <p class="text-white/60">Data kehadiran siswa hari ini akan muncul di sini</p>
                </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-white/60">
                <p>&copy; 2026 Madrasah Tsanawiyah - Sistem Presensi Digital</p>
            </div>
        </div>
    </div>

    <script>
        // Auto refresh every 30 seconds
        setInterval(function() {
            location.reload();
        }, 30000);

        // Add loading animation
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.animate-fade-in, .animate-slide-up');
            elements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
    @include('partials.sweetalerts')
</body>
</html>
