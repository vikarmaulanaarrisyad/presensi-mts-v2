<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitur Dikunci - MTs Mambaul Ulum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8fafc;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            color: #334155;
            padding: 20px;
        }
        .lock-container {
            background: white;
            padding: 50px 40px;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            text-align: center;
            max-width: 500px;
            width: 100%;
            border: 1px solid #e2e8f0;
        }
        .icon-lock {
            font-size: 4rem;
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 20px auto;
        }
        h2 {
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 15px;
        }
        p {
            color: #64748b;
            font-weight: 500;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn-back {
            background: #0f172a;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: 0.2s;
        }
        .btn-back:hover {
            background: #1e293b;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
        }
    </style>
</head>
<body>

    <div class="lock-container">
        <div class="icon-lock">
            <i class="fa-solid fa-lock"></i>
        </div>
        <h2>Akses Dibatasi</h2>
        <p>Maaf, fitur <b>{{ $feature->nama_fitur }}</b> saat ini belum diaktifkan oleh pihak sekolah. Silakan hubungi admin / pihak TU sekolah untuk info lebih lanjut.</p>
        
        <a href="javascript:history.back()" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Halaman Sebelumnya
        </a>
    </div>

    @include('partials.sweetalerts')
</body>
</html>
