<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - MTs Mambaul Ulum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link class="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    {{ $styles ?? '' }}

    <style>
        :root {
            --sidebar-bg: #114224; 
            --sidebar-hover: rgba(255, 255, 255, 0.08);
            --sidebar-active: #175830;
            --accent-green: #198754;
            --bg-light: #f8fafc;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: #334155;
            min-height: 100vh;
            display: flex;
            margin: 0;
        }

        /* SIDEBAR STYLE */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            padding: 2rem 1rem 1.5rem 1rem;
        }

        .sidebar-brand {
            text-align: center;
            padding-bottom: 2rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .avatar-circle {
            width: 85px;
            height: 85px;
            background-color: #ffffff;
            border-radius: 50%;
            margin: 0 auto 1rem auto;
        }

        .brand-title {
            color: #ffffff;
            font-weight: 800;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .brand-subtitle {
            color: #2ec4b6;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .menu-section-title {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0.5rem 1rem;
            margin-top: 1rem;
            margin-bottom: 0.25rem;
        }

        .nav-link-custom {
            color: rgba(255, 255, 255, 0.75) !important;
            font-weight: 500;
            font-size: 0.88rem;
            padding: 0.75rem 1rem !important;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            margin-bottom: 0.25rem;
        }

        .nav-link-custom i {
            width: 20px;
            text-align: center;
            font-size: 1.05rem;
        }

        .nav-link-custom:hover {
            color: #ffffff !important;
            background: var(--sidebar-hover);
        }

        .nav-link-custom.active {
            color: #ffffff !important;
            background: var(--sidebar-active);
            font-weight: 600;
            border-left: 4px solid #2ec4b6;
            border-radius: 4px 8px 8px 4px;
            padding-left: calc(1rem - 4px) !important;
        }

        .logout-area {
            margin-top: auto;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-logout-sidebar {
            color: #fca5a5 !important;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .btn-logout-sidebar:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444 !important;
        }

        /* AREA KONTEN UTAMA DATA SISWA */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 2.5rem;
            min-height: 100vh;
        }

        .top-header-panel {
            background: #ffffff;
            border-radius: 16px;
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
            border: 1px solid #e2e8f0;
        }

        .panel-title {
            color: #0f172a;
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 4px;
        }

        .panel-subtitle {
            color: #64748b;
            font-size: 0.88rem;
        }

        .stat-card-white {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.82rem;
            font-weight: 500;
        }

        .stat-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        /* TABEL DATA STYLE */
        .table-container-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
            overflow: hidden;
            padding: 1.5rem;
        }

        .font-mono-custom {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.82rem;
        }

        /* MODAL DESIGN */
        .modal-style-content {
            border-radius: 16px;
            border: none;
            overflow: hidden;
        }

        .modal-style-header {
            background: var(--sidebar-bg);
            color: #ffffff;
            padding: 1.25rem 1.5rem;
        }

        @media (max-width: 991.98px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; height: auto; position: relative; padding: 1rem; }
            .main-content { margin-left: 0; width: 100%; padding: 1.25rem; }
            .avatar-circle { display: none; }
            .sidebar-brand { padding-bottom: 0; margin-bottom: 1rem; border-bottom: none; text-align: left; }
        }
    </style>

    {{ $styles ?? '' }}
</head>
<body>
    <x-sidebar-admin />
    
    <div class="main-content">
        <x-header-admin :title="$headerTitle ?? 'Dashboard'" :subtitle="$headerSubtitle ?? ''">
            {{ $headerActions ?? '' }}
        </x-header-admin>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <strong>Sukses!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> <strong>Peringatan!</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-xmark me-2"></i> <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{ $slot }}
    </div>

    {{ $scripts ?? '' }}
</body>
</html>