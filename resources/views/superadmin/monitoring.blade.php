<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Server Monitoring - Superadmin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
  *{font-family:'Inter',sans-serif;box-sizing:border-box;}
  body{background:#060d1a;color:#e2e8f0;min-height:100vh;}
  .mono{font-family:'JetBrains Mono',monospace;}
  /* TOPBAR */
  .topbar{background:rgba(15,23,42,.9);backdrop-filter:blur(20px);border-bottom:1px solid rgba(255,255,255,.07);padding:14px 28px;position:sticky;top:0;z-index:100;}
  /* SIDEBAR */
  .sidebar{width:240px;background:rgba(15,23,42,.6);backdrop-filter:blur(20px);border-right:1px solid rgba(255,255,255,.07);min-height:100vh;position:fixed;top:0;left:0;padding-top:70px;display:flex;flex-direction:column;}
  .sidebar a{display:flex;align-items:center;gap:10px;padding:12px 24px;color:rgba(226,232,240,.6);font-size:.875rem;font-weight:500;text-decoration:none;transition:.2s;border-left:3px solid transparent;}
  .sidebar a:hover,.sidebar a.active{color:#fff;background:rgba(255,255,255,.05);border-left-color:#6366f1;}
  .main{margin-left:240px;padding:90px 28px 40px;}
  /* CARDS */
  .glass{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:16px;backdrop-filter:blur(10px);}
  .glass-header{padding:16px 20px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:10px;}
  .glass-body{padding:20px;}
  /* STAT CARDS */
  .stat{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px;position:relative;overflow:hidden;transition:.3s;}
  .stat:hover{background:rgba(255,255,255,.07);transform:translateY(-3px);box-shadow:0 12px 40px rgba(0,0,0,.4);}
  .stat::before{content:'';position:absolute;top:-30px;right:-30px;width:100px;height:100px;border-radius:50%;opacity:.06;}
  .stat-label{font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(226,232,240,.5);margin-bottom:8px;}
  .stat-value{font-size:2rem;font-weight:800;line-height:1;}
  .stat-sub{font-size:.72rem;color:rgba(226,232,240,.4);margin-top:6px;}
  /* HEALTH */
  .health-ok{color:#10b981;}
  .health-fail{color:#ef4444;}
  /* PROGRESS */
  .prog-track{height:6px;background:rgba(255,255,255,.08);border-radius:10px;overflow:hidden;}
  .prog-fill{height:100%;border-radius:10px;transition:width .8s ease;}
  /* BLINK */
  .blink{animation:blink 1.4s infinite;}
  @keyframes blink{0%,100%{opacity:1}50%{opacity:.15}}
  /* PULSE DOT */
  .pulse{display:inline-block;width:8px;height:8px;border-radius:50%;background:#10b981;position:relative;}
  .pulse::after{content:'';position:absolute;top:-3px;left:-3px;width:14px;height:14px;border-radius:50%;background:rgba(16,185,129,.3);animation:pulse 1.5s infinite;}
  @keyframes pulse{0%{transform:scale(1);opacity:1}100%{transform:scale(2);opacity:0}}
  /* TABLE */
  .dark-table{width:100%;border-collapse:collapse;}
  .dark-table th{font-size:.68rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:rgba(226,232,240,.4);padding:10px 14px;border-bottom:1px solid rgba(255,255,255,.07);}
  .dark-table td{padding:10px 14px;border-bottom:1px solid rgba(255,255,255,.04);font-size:.83rem;vertical-align:middle;}
  .dark-table tr:hover td{background:rgba(255,255,255,.03);}
  /* BADGE */
  .bd{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:.68rem;font-weight:700;letter-spacing:.05em;}
  .bd-green{background:rgba(16,185,129,.15);color:#10b981;border:1px solid rgba(16,185,129,.3);}
  .bd-red{background:rgba(239,68,68,.15);color:#ef4444;border:1px solid rgba(239,68,68,.3);}
  .bd-yellow{background:rgba(245,158,11,.15);color:#f59e0b;border:1px solid rgba(245,158,11,.3);}
  .bd-blue{background:rgba(99,102,241,.15);color:#818cf8;border:1px solid rgba(99,102,241,.3);}
  .bd-gray{background:rgba(148,163,184,.1);color:#94a3b8;border:1px solid rgba(148,163,184,.2);}
  /* SCROLLBAR */
  ::-webkit-scrollbar{width:5px;height:5px;}
  ::-webkit-scrollbar-track{background:transparent;}
  ::-webkit-scrollbar-thumb{background:rgba(255,255,255,.15);border-radius:10px;}
  /* LOG */
  .log-box{background:#000;border-radius:0 0 14px 14px;padding:16px;max-height:200px;overflow-y:auto;font-size:.68rem;line-height:1.7;color:#22c55e;}
  /* DEVICE ICON */
  .dev-online{color:#10b981;}
  .dev-offline{color:#6b7280;}
  /* COUNTER ANIMATION */
  .count-up{display:inline-block;}
  /* CHART */
  canvas{display:block;}
  /* REFRESH PILL */
  .refresh-pill{background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.3);color:#818cf8;border-radius:20px;padding:4px 12px;font-size:.72rem;font-weight:600;}
</style>
</head>
<body>

{{-- TOP BAR --}}
<nav class="topbar d-flex align-items-center justify-content-between">
  <div class="d-flex align-items-center gap-3">
    <div class="pulse"></div>
    <span class="fw-bold" style="font-size:.95rem">Server Monitoring</span>
    <span class="text-muted small">/ Superadmin</span>
  </div>
  <div class="d-flex align-items-center gap-3">
    <span class="mono small" id="live-clock" style="color:#818cf8"></span>
    <button onclick="location.reload()" class="refresh-pill btn btn-sm"><i class="fa-solid fa-rotate me-1"></i>Refresh</button>
    <a href="{{ route('superadmin.dashboard') }}" class="btn btn-sm" style="background:rgba(255,255,255,.07);color:#e2e8f0;border:1px solid rgba(255,255,255,.1);border-radius:8px;font-size:.8rem">
      <i class="fa-solid fa-arrow-left me-1"></i>Dashboard
    </a>
  </div>
</nav>

{{-- SIDEBAR --}}
<aside class="sidebar">
  <a href="{{ route('superadmin.dashboard') }}"><i class="fa-solid fa-gem" style="color:#f59e0b;width:18px"></i> Fitur Premium</a>
  <a href="{{ route('superadmin.monitoring') }}" class="active"><i class="fa-solid fa-server" style="color:#6366f1;width:18px"></i> Server Monitoring</a>
  <div class="mt-auto p-4">
    <a href="{{ route('logout') }}" class="btn btn-sm w-100" style="background:rgba(239,68,68,.1);color:#ef4444;border:1px solid rgba(239,68,68,.2);border-radius:8px">
      <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
    </a>
  </div>
</aside>

{{-- MAIN --}}
<main class="main">

  {{-- ── STAT CARDS ── --}}
  <div class="row g-3 mb-4">
    @php
    $statCards = [
      ['label'=>'Total Siswa','value'=>number_format($totalSiswa),'sub'=>'Terdaftar','icon'=>'fa-users','color'=>'#6366f1'],
      ['label'=>'Absensi Hari Ini','value'=>number_format($attendanceToday),'sub'=>date('d M Y'),'icon'=>'fa-fingerprint','color'=>'#10b981'],
      ['label'=>'Total Absensi','value'=>number_format($totalAttendance),'sub'=>'Semua waktu','icon'=>'fa-calendar-check','color'=>'#f59e0b'],
      ['label'=>'Perangkat ESP32','value'=>number_format($totalDevice),'sub'=>'Terdaftar','icon'=>'fa-microchip','color'=>'#3b82f6'],
      ['label'=>'Ukuran DB','value'=>$dbSizeMb.' MB','sub'=>$dbName,'icon'=>'fa-database','color'=>'#8b5cf6'],
      ['label'=>'Disk Bebas','value'=>$diskFreeFmt,'sub'=>"Dari $diskTotalFmt",'icon'=>'fa-hard-drive','color'=>'#14b8a6'],
      ['label'=>'Memory PHP','value'=>$memoryUsage.' MB','sub'=>"Limit $memoryLimit",'icon'=>'fa-microchip','color'=>'#f43f5e'],
      ['label'=>'Pembayaran Pending','value'=>number_format($pendingTransaksi),'sub'=>'Menunggu verifikasi','icon'=>'fa-hourglass-half','color'=>'#f97316'],
    ];
    @endphp
    @foreach($statCards as $s)
    <div class="col-6 col-md-3">
      <div class="stat">
        <div class="stat-label">{{ $s['label'] }}</div>
        <div class="stat-value count-up" style="color:{{ $s['color'] }}">{{ $s['value'] }}</div>
        <div class="stat-sub"><i class="fa-solid {{ $s['icon'] }} me-1" style="color:{{ $s['color'] }};opacity:.7"></i>{{ $s['sub'] }}</div>
        <div style="position:absolute;top:16px;right:16px;opacity:.08;font-size:2.5rem"><i class="fa-solid {{ $s['icon'] }}"></i></div>
      </div>
    </div>
    @endforeach
  </div>

  {{-- ── ROW 2: Chart + Health + Devices ── --}}
  <div class="row g-4 mb-4">

    {{-- Chart 7 Hari --}}
    <div class="col-lg-8">
      <div class="glass h-100">
        <div class="glass-header">
          <i class="fa-solid fa-chart-line" style="color:#6366f1"></i>
          <span class="fw-bold">Tren Absensi 7 Hari Terakhir</span>
          <span class="ms-auto bd bd-blue">Chart.js</span>
        </div>
        <div class="glass-body">
          <canvas id="attendanceChart" style="max-height:220px"></canvas>
        </div>
      </div>
    </div>

    {{-- System Health --}}
    <div class="col-lg-4">
      <div class="glass h-100">
        <div class="glass-header">
          <i class="fa-solid fa-heart-pulse" style="color:#ef4444"></i>
          <span class="fw-bold">System Health</span>
          @php $allOk = collect($healthChecks)->every(fn($h)=>$h['ok']); @endphp
          <span class="ms-auto {{ $allOk ? 'bd bd-green' : 'bd bd-red' }}">
            <span class="blink me-1">●</span> {{ $allOk ? 'ALL OK' : 'ISSUE' }}
          </span>
        </div>
        <div class="glass-body p-0">
          @foreach($healthChecks as $h)
          <div class="d-flex align-items-center justify-content-between px-4 py-2" style="border-bottom:1px solid rgba(255,255,255,.04)">
            <div>
              <div style="font-size:.8rem;font-weight:600">{{ $h['label'] }}</div>
              <div style="font-size:.68rem;color:rgba(226,232,240,.4)">{{ $h['info'] }}</div>
            </div>
            <i class="fa-solid {{ $h['ok'] ? 'fa-circle-check health-ok' : 'fa-circle-xmark health-fail' }} fa-lg"></i>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  {{-- ── ROW 3: Recent Attendance + Devices + Kelas ── --}}
  <div class="row g-4 mb-4">

    {{-- Absensi Terbaru --}}
    <div class="col-lg-5">
      <div class="glass h-100">
        <div class="glass-header">
          <i class="fa-solid fa-clock-rotate-left" style="color:#f59e0b"></i>
          <span class="fw-bold">Absensi Terbaru</span>
          <span class="ms-auto bd bd-yellow">Live</span>
        </div>
        <div style="overflow-x:auto">
          <table class="dark-table">
            <thead><tr><th>Nama</th><th>Kelas</th><th>Status</th><th>Waktu</th></tr></thead>
            <tbody>
              @forelse($recentAttendances as $att)
              <tr>
                <td class="fw-semibold" style="font-size:.8rem">{{ $att->name }}</td>
                <td><span class="bd bd-gray">{{ $att->kelas }}</span></td>
                <td>
                  @if($att->status === 'Hadir')
                    <span class="bd bd-green">{{ $att->status }}</span>
                  @elseif($att->status === 'Alpa')
                    <span class="bd bd-red">{{ $att->status }}</span>
                  @else
                    <span class="bd bd-yellow">{{ $att->status }}</span>
                  @endif
                </td>
                <td class="mono" style="font-size:.7rem;color:rgba(226,232,240,.5)">{{ \Carbon\Carbon::parse($att->created_at)->format('H:i') }}</td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center py-4" style="color:rgba(226,232,240,.3)">Belum ada absensi hari ini</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- ESP32 Devices --}}
    <div class="col-lg-4">
      <div class="glass h-100">
        <div class="glass-header">
          <i class="fa-solid fa-tower-broadcast" style="color:#3b82f6"></i>
          <span class="fw-bold">Perangkat ESP32</span>
        </div>
        <div class="glass-body p-0">
          @forelse($devices as $dev)
          @php
            $isOnline = !empty($dev->last_ping) && \Carbon\Carbon::parse($dev->last_ping)->diffInMinutes(now()) < 5;
          @endphp
          <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,.04)">
            <i class="fa-solid fa-microchip fa-lg {{ $isOnline ? 'dev-online' : 'dev-offline' }}"></i>
            <div class="flex-grow-1">
              <div style="font-size:.85rem;font-weight:600">{{ $dev->nama_alat }}</div>
              <div style="font-size:.7rem;color:rgba(226,232,240,.4)">
                @if($dev->last_ping) Ping: {{ \Carbon\Carbon::parse($dev->last_ping)->format('H:i:s') }} @else Belum pernah ping @endif
              </div>
            </div>
            @if($isOnline)
              <span class="bd bd-green"><span class="blink me-1">●</span>Online</span>
            @else
              <span class="bd bd-gray">Offline</span>
            @endif
          </div>
          @empty
          <div class="p-4 text-center" style="color:rgba(226,232,240,.3)">Tidak ada perangkat</div>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Absensi per Kelas --}}
    <div class="col-lg-3">
      <div class="glass h-100">
        <div class="glass-header">
          <i class="fa-solid fa-school" style="color:#8b5cf6"></i>
          <span class="fw-bold">Per Kelas Hari Ini</span>
        </div>
        <div class="glass-body">
          @forelse($perKelas as $k)
          @php $maxK = $perKelas->max('total'); $pctK = $maxK > 0 ? round($k->total/$maxK*100) : 0; @endphp
          <div class="mb-3">
            <div class="d-flex justify-content-between mb-1">
              <span style="font-size:.8rem;font-weight:600">{{ $k->kelas }}</span>
              <span style="font-size:.75rem;color:#8b5cf6;font-weight:700">{{ $k->total }}</span>
            </div>
            <div class="prog-track"><div class="prog-fill" style="width:{{ $pctK }}%;background:linear-gradient(90deg,#6366f1,#8b5cf6)"></div></div>
          </div>
          @empty
          <div class="text-center py-3" style="color:rgba(226,232,240,.3);font-size:.85rem">Belum ada absensi</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  {{-- ── ROW 4: Disk + Memory + DB + PHP ── --}}
  <div class="row g-4 mb-4">

    {{-- Disk --}}
    <div class="col-lg-3">
      <div class="glass h-100">
        <div class="glass-header">
          <i class="fa-solid fa-hard-drive" style="color:#14b8a6"></i>
          <span class="fw-bold">Disk Usage</span>
        </div>
        <div class="glass-body text-center">
          @php $diskColor = $diskPct>85?'#ef4444':($diskPct>60?'#f59e0b':'#10b981'); @endphp
          <div style="font-size:3rem;font-weight:900;color:{{ $diskColor }}">{{ $diskPct }}%</div>
          <div style="font-size:.75rem;color:rgba(226,232,240,.4)" class="mb-3">{{ $diskUsedFmt }} / {{ $diskTotalFmt }}</div>
          <div class="prog-track mb-3"><div class="prog-fill" style="width:{{ $diskPct }}%;background:{{ $diskColor }}"></div></div>
          <div class="d-flex justify-content-between" style="font-size:.72rem">
            <span style="color:rgba(226,232,240,.4)">Bebas: <span style="color:#10b981;font-weight:700">{{ $diskFreeFmt }}</span></span>
          </div>
        </div>
      </div>
    </div>

    {{-- Memory --}}
    <div class="col-lg-3">
      <div class="glass h-100">
        <div class="glass-header">
          <i class="fa-solid fa-memory" style="color:#f43f5e"></i>
          <span class="fw-bold">PHP Memory</span>
        </div>
        <div class="glass-body text-center">
          @php $memLimitI=(int)ini_get('memory_limit'); $memPct=$memLimitI>0?round($memoryUsage/$memLimitI*100,1):0; $mc=$memPct>80?'#ef4444':($memPct>50?'#f59e0b':'#6366f1'); @endphp
          <div style="font-size:3rem;font-weight:900;color:{{ $mc }}">{{ $memPct }}%</div>
          <div style="font-size:.75rem;color:rgba(226,232,240,.4)" class="mb-3">{{ $memoryUsage }} MB / {{ $memoryLimit }}</div>
          <div class="prog-track mb-3"><div class="prog-fill" style="width:{{ min($memPct,100) }}%;background:{{ $mc }}"></div></div>
          <div style="font-size:.72rem;color:rgba(226,232,240,.4)">Peak: <span style="color:{{ $mc }};font-weight:700">{{ $memoryPeak }} MB</span></div>
        </div>
      </div>
    </div>

    {{-- PHP & Server --}}
    <div class="col-lg-3">
      <div class="glass h-100">
        <div class="glass-header">
          <i class="fa-brands fa-php" style="color:#8993be"></i>
          <span class="fw-bold">PHP & Server</span>
        </div>
        <div class="glass-body p-0">
          @php $prows=[['PHP','<span class="bd bd-blue mono">'.PHP_VERSION.'</span>'],['Laravel','<span class="bd bd-red mono">v'.$laravelVersion.'</span>'],['OS','<span style="font-size:.75rem">'.e($osInfo).'</span>'],['Env','<span class="bd '.($environment==="production"?"bd-green":"bd-yellow").'">'.strtoupper($environment).'</span>'],['Debug','<span class="bd '.(str_contains($debugMode,"ON")?"bd-red":"bd-green").'">'.e($debugMode).'</span>'],['Upload Max','<span class="bd bd-gray mono">'.$maxUpload.'</span>']]; @endphp
          @foreach($prows as $pr)
          <div class="d-flex align-items-center justify-content-between px-4 py-2" style="border-bottom:1px solid rgba(255,255,255,.04)">
            <span style="font-size:.75rem;color:rgba(226,232,240,.4);font-weight:600">{{ $pr[0] }}</span>
            <div style="font-size:.78rem">{!! $pr[1] !!}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- DB Info --}}
    <div class="col-lg-3">
      <div class="glass h-100">
        <div class="glass-header">
          <i class="fa-solid fa-database" style="color:#0ea5e9"></i>
          <span class="fw-bold">Database</span>
        </div>
        <div class="glass-body p-0">
          @php $drows=[['Host','<span class="mono" style="font-size:.75rem">'.$dbHost.':'.$dbPort.'</span>'],['DB Name','<span class="mono bd bd-blue">'.$dbName.'</span>'],['MySQL','<span class="bd bd-green">'.e($dbVersion).'</span>'],['Ukuran','<span class="bd bd-blue">'.$dbSizeMb.' MB</span>'],['Tabel','<span class="bd bd-gray">'.count($tables).' tabel</span>'],['Session',ucfirst($sessionDriver).' / '.ucfirst($cacheDriver)]]; @endphp
          @foreach($drows as $dr)
          <div class="d-flex align-items-center justify-content-between px-4 py-2" style="border-bottom:1px solid rgba(255,255,255,.04)">
            <span style="font-size:.75rem;color:rgba(226,232,240,.4);font-weight:600">{{ $dr[0] }}</span>
            <div style="font-size:.78rem">{!! $dr[1] !!}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  {{-- ── ROW 5: DB Tables + Log ── --}}
  <div class="row g-4 mb-4">
    <div class="col-lg-7">
      <div class="glass">
        <div class="glass-header">
          <i class="fa-solid fa-table" style="color:#6366f1"></i>
          <span class="fw-bold">Tabel Database: <span class="mono" style="color:#818cf8">{{ $dbName }}</span></span>
          <span class="ms-auto bd bd-blue">{{ $dbSizeMb }} MB</span>
        </div>
        <div style="overflow-x:auto;max-height:300px;overflow-y:auto">
          <table class="dark-table">
            <thead><tr><th>#</th><th>Nama Tabel</th><th class="text-end">Baris</th><th class="text-end">Ukuran</th></tr></thead>
            <tbody>
              @foreach($tables as $i=>$t)
              <tr>
                <td style="color:rgba(226,232,240,.3);font-size:.75rem">{{ $i+1 }}</td>
                <td class="mono fw-semibold" style="font-size:.8rem">{{ $t->table_name }}</td>
                <td class="text-end" style="font-size:.8rem">{{ number_format($t->table_rows) }}</td>
                <td class="text-end mono" style="font-size:.78rem;color:rgba(226,232,240,.5)">{{ $t->size_kb }} KB</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="glass h-100">
        <div class="glass-header justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-terminal" style="color:#22c55e"></i>
            <span class="fw-bold">Laravel Log</span>
          </div>
          <span class="bd bd-gray mono">{{ $logSize }}</span>
        </div>
        <div class="log-box">@if(count($logLastLines)>0){{ implode('',$logLastLines) }}@else<span style="color:rgba(226,232,240,.3)">Log kosong.</span>@endif</div>
      </div>
    </div>
  </div>

  {{-- ── ROW 6: API Security ── --}}
  <div class="glass mb-4">
    <div class="glass-header justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <i class="fa-solid fa-shield-halved" style="color:#f59e0b"></i>
        <span class="fw-bold">Keamanan API ESP32 — Rate Limiting</span>
      </div>
      <span class="bd bd-green"><span class="blink me-1">●</span>Aktif</span>
    </div>
    <div class="glass-body">
      <div class="row g-3">
        @php
        $eps=[
          ['/api/presensi','POST','30 req/menit','fa-fingerprint','#6366f1','rgba(99,102,241,.1)','Kirim data absensi dari alat'],
          ['/api/cek-status-alat','GET','20 req/menit','fa-tower-broadcast','#10b981','rgba(16,185,129,.1)','Polling perintah (normal: 12/mnt)'],
          ['/api/konfirmasi-enroll','POST','10 req/menit','fa-user-check','#f59e0b','rgba(245,158,11,.1)','Konfirmasi registrasi sidik jari'],
          ['/api/konfirmasi-hapus','POST','10 req/menit','fa-user-minus','#ef4444','rgba(239,68,68,.1)','Konfirmasi penghapusan sidik jari'],
          ['/api/cek','GET','Bebas','fa-plug-circle-check','#64748b','rgba(100,116,139,.1)','Tes koneksi publik'],
        ];
        @endphp
        @foreach($eps as $ep)
        <div class="col-md-6 col-lg-4">
          <div class="rounded-3 p-3" style="background:{{ $ep[5] }};border:1px solid {{ $ep[4] }}22">
            <div class="d-flex align-items-start gap-2">
              <i class="fa-solid {{ $ep[3] }} fa-lg mt-1" style="color:{{ $ep[4] }}"></i>
              <div>
                <div class="d-flex align-items-center gap-1 mb-1">
                  <span class="bd {{ $ep[1]==='GET'?'bd-blue':'bd-green' }} mono" style="font-size:.62rem">{{ $ep[1] }}</span>
                  <span class="mono fw-bold" style="font-size:.75rem;color:#e2e8f0">{{ $ep[0] }}</span>
                </div>
                <div style="font-size:.72rem;color:rgba(226,232,240,.5)">{{ $ep[6] }}</div>
                <div class="mt-2" style="font-size:.75rem;font-weight:700;color:{{ $ep[4] }}"><i class="fa-solid fa-gauge-high me-1"></i>{{ $ep[2] }}</div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
// Live Clock
function updateClock(){const n=new Date();document.getElementById('live-clock').textContent=n.toLocaleTimeString('id-ID',{hour12:false});}
updateClock();setInterval(updateClock,1000);

// Attendance Chart
const ctx = document.getElementById('attendanceChart').getContext('2d');
new Chart(ctx,{
  type:'bar',
  data:{
    labels: {!! json_encode($chartLabels) !!},
    datasets:[
      {label:'Hadir',data:{!! json_encode($chartHadir) !!},backgroundColor:'rgba(16,185,129,.7)',borderColor:'#10b981',borderWidth:2,borderRadius:6},
      {label:'Izin/Sakit',data:{!! json_encode($chartIzin) !!},backgroundColor:'rgba(245,158,11,.7)',borderColor:'#f59e0b',borderWidth:2,borderRadius:6},
      {label:'Alpa',data:{!! json_encode($chartAlpa) !!},backgroundColor:'rgba(239,68,68,.7)',borderColor:'#ef4444',borderWidth:2,borderRadius:6},
    ]
  },
  options:{
    responsive:true,maintainAspectRatio:true,
    plugins:{
      legend:{labels:{color:'rgba(226,232,240,.6)',font:{size:12}}},
      tooltip:{backgroundColor:'rgba(15,23,42,.95)',titleColor:'#e2e8f0',bodyColor:'rgba(226,232,240,.7)',borderColor:'rgba(255,255,255,.1)',borderWidth:1}
    },
    scales:{
      x:{ticks:{color:'rgba(226,232,240,.5)',font:{size:11}},grid:{color:'rgba(255,255,255,.05)'}},
      y:{ticks:{color:'rgba(226,232,240,.5)',font:{size:11}},grid:{color:'rgba(255,255,255,.05)'},beginAtZero:true}
    }
  }
});
</script>
</body>
</html>
