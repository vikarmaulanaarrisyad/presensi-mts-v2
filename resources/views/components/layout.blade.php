<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring Presensi Real-Time</title>
    
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- RESET & BASIC STYLING --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            display: flex;
            background-color: #f8fafc;
            min-height: 100vh;
            color: #1e293b;
            overflow-x: hidden;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1b4d22 0%, #143a1a 100%);
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: fixed;
            box-shadow: 6px 0 30px rgba(0, 0, 0, 0.12);
            z-index: 100;
        }
        
        .brand-wrapper {
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.15);
        }

        .brand-logo-frame {
            width: 85px; 
            height: 85px; 
            object-fit: contain;
            background: white;
            padding: 6px;
            border-radius: 50%; 
            box-shadow: 0 6px 16px rgba(0,0,0,0.25);
            transition: transform 0.3s ease;
            border: 3px solid rgba(255, 255, 255, 0.2);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .brand-title {
            font-size: 14px; 
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.4;
            color: #ffffff;
        }

        .brand-subtitle {
            font-size: 11px; 
            font-weight: 600;
            color: #34d399; 
            letter-spacing: 0.5px;
        }

        .menu-container {
            padding: 24px 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-group-title {
            font-size: 11px; 
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.45);
            padding: 0 24px 12px 24px;
            font-weight: 700;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px; 
            padding: 14px 24px; 
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            font-size: 14px; 
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 5px solid transparent;
        }

        .menu-link i {
            font-size: 16px; 
            width: 20px;
            text-align: center;
        }

        .menu-link:hover {
            background: rgba(255, 255, 255, 0.06);
            color: white;
        }

        .menu-link.active {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.02) 100%);
            color: white;
            font-weight: 600;
            border-left-color: #34d399;
        }

        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 15px 0;
            background: rgba(0, 0, 0, 0.1);
        }

        .btn-logout {
            color: #fca5a5 !important;
        }

        /* --- KONTEN UTAMA --- */
        .main-wrapper {
            margin-left: 280px;
            flex-grow: 1;
            padding: 40px;
            max-width: calc(100% - 280px);
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            background: white;
            padding: 18px 30px;
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.02);
            border: 1px solid #f1f5f9;
        }

        .header-title-area h1 {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
        }

        .header-title-area p {
            font-size: 12px;
            color: #64748b;
        }

        .btn-pdf-premium {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-left: 20px;
            border-left: 1px solid #e2e8f0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 35px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
        }

        .stat-label {
            font-size: 12px;
            color: #64748b;
        }

        .stat-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .icon-total { background: #f0fdf4; color: #16a34a; }
        .icon-hadir { background: #eff6ff; color: #2563eb; }
        .icon-izin { background: #fef3c7; color: #d97706; }

        /* --- NOTIFIKASI --- */
        .alert-premium {
            background: #ecfdf5;
            color: #065f46;
            padding: 16px 24px;
            border-radius: 12px;
            margin-bottom: 15px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #a7f3d0;
        }

        /* --- TABEL LOG ABSENSI --- */
        .table-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            border: 1px solid #f1f5f9;
        }

        .table-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .table-card-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #f8fafc;
            color: #475569;
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 700;
            padding: 16px 20px;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 16px 20px;
            font-size: 13.5px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Badge Status */
        .status-badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 11.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .badge-premium-hadir { background: #e0f2fe; color: #0369a1; }
        .badge-premium-izin { background: #dcfce7; color: #15803d; }
        .badge-premium-absen { background: #fee2e2; color: #b91c1c; }

        /* --- TOMBOL INPUT IZIN --- */
        .btn-action-izin {
            background-color: #3b82f6; 
            color: white;
            border: none;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.15);
        }

        .btn-action-izin:hover {
            background-color: #2563eb;
            transform: translateY(-1px);
        }

        /* --- TOMBOL INPUT ALPHA (TAMBAHAN BARU) --- */
        .btn-action-alpha {
            background-color: #ef4444; 
            color: white;
            border: none;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.15);
        }

        .btn-action-alpha:hover {
            background-color: #dc2626;
            transform: translateY(-1px);
        }

        /* --- MODAL DIALOG POPUP --- */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0; pointer-events: none;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1; pointer-events: auto;
        }

        .modal-box {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 450px;
            padding: 30px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 12.5px; font-weight: 600; color: #475569; margin-bottom: 8px; }
        .form-input { width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13.5px; outline: none; }
        
        /* Tombol Simpan Form Terintegrasi Kirim WhatsApp */
        .btn-submit-wa { 
            width: 100%; 
            background: #25d366; 
            color: white; 
            border: none; 
            padding: 12px; 
            border-radius: 8px; 
            font-weight: 700; 
            font-size: 13.5px; 
            cursor: pointer; 
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(37, 211, 102, 0.25);
        }
        .btn-submit-wa:hover { background: #1ebd58; }
    </style>

    {{ $styles ?? '' }}
</head>
<body>
    <x-sidebar />
    <div class="main-wrapper">
        <x-header :title="$title ?? 'Dashboard'" :subtitle="$subtitle ?? ''">
            {{ $headerActions ?? '' }}
        </x-header>

        @if(session('success'))
            <div class="alert-premium">
                <i class="fa-solid fa-circle-check" style="font-size: 18px; color: #10b981;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{ $slot }}
    </div>

    {{ $scripts ?? '' }}
</body>
</html>