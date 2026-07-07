<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Presensi - MTS MAMBAUL ULUM Kota Tegal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            max-width: 450px;
            width: 100%;        
            margin: auto;
            padding: 20px;
        }
        .card-login {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .login-header img {
            width: 110px; 
            height: 110px;
            margin-bottom: 12px;
            /* FIX: Efek filter drop-shadow hitam sudah dihapus total di sini biar bersih */
            object-fit: contain;
        }
        .login-header h5 {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            font-size: 1.1rem;
        }
        .login-header p {
            font-size: 0.85rem;
            margin-bottom: 0;
            opacity: 0.9;
        }
        .btn-login {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #1b5e20 0%, #123c14 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
            color: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: #2e7d32;
            box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
        }
        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 0.8rem;
            color: #666;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="card card-login">
        
        <div class="login-header d-flex flex-column align-items-center justify-content-center">
            <img src="{{ asset('mts.webp') }}" alt="Logo Madrasah">
            <h5>Sistem Presensi Fingerprint</h5>
            <p>MTS MAMBAUL ULUM Kota Tegal</p>
        </div>

        <div class="card-body p-4">
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label text-secondary fw-semibold">Nama Pengguna / NIS</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan NIS atau Username" required autocomplete="off">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-secondary fw-semibold">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Kata Sandi" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="role" class="form-label text-secondary fw-semibold">Hak Akses Sebagai</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="fas fa-users-cog"></i></span>
                        <select name="role" id="role" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Role --</option>
                             <option value="admin">Kepala Sekolah</option>
                             <option value="guru">Guru</option>
                             <option value="admin">admin</option>
                             <option value="siswa">Siswa</option>
                        </select>
                    </div>
                </div>

                <div class="d-grid shadow-sm">
                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Sistem
                    </button>
                </div>
            </form>

        </div>
    </div>

    <p class="footer-text">
        &copy; {{ date('Y') }} Mandiri Cloud Synchronized Attendance System.<br>
        MTS MAMBAUL ULUM Kota Tegal
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>