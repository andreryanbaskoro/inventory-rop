@php($asetMazer = asset('mazer-1.0.0/dist'))
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Cahaya Mulya Mart</title>
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $asetMazer }}/assets/css/bootstrap.css">
    <link rel="stylesheet" href="{{ $asetMazer }}/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ $asetMazer }}/assets/css/app.css">
    <link rel="stylesheet" href="{{ asset('css/premium.css') }}">
    <style>
        body {
            background-color: #f1f5f9;
        }
        #auth {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .auth-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            display: flex;
        }
        .auth-left {
            flex: 1;
            padding: 4rem;
        }
        .auth-right {
            flex: 1;
            background: linear-gradient(135deg, var(--cm-primary-dark), var(--cm-primary));
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            color: #fff;
        }
        @media (max-width: 991px) {
            .auth-right {
                display: none;
            }
            .auth-left {
                padding: 3rem 2rem;
            }
        }
        .auth-logo-box {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--cm-primary), var(--cm-accent));
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
            flex-shrink: 0;
        }
        .auth-logo-box svg {
            width: 24px;
            height: 24px;
        }
        .pattern-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0);
            background-size: 24px 24px;
            opacity: 0.6;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        .form-control-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 1.15rem;
            color: var(--cm-text-muted);
            pointer-events: none;
        }
        .has-icon-left .form-control {
            padding-left: 3rem !important;
        }
    </style>
</head>

<body>
    <div id="auth">
        <div class="auth-card">
            <!-- Sisi Kiri (Form) -->
            <div class="auth-left">
                <div class="mb-4">
                    <a href="{{ route('login') }}" class="text-decoration-none d-inline-flex align-items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Cahaya Mulya Mart" style="height: 46px; width: auto; border-radius: 8px;">
                        <div>
                            <h5 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.02em;">Cahaya Mulya Mart</h5>
                            <span class="d-block small" style="color: var(--cm-text-muted);">Sistem Inventaris ROP</span>
                        </div>
                    </a>
                </div>
                <h3 class="fw-bold mb-2">Selamat Datang</h3>
                <p class="mb-4" style="color: var(--cm-text-muted);">Silahkan masuk ke akun Anda untuk melanjutkan.</p>

                <form action="{{ route('login.proses') }}" method="post">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-3">
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Alamat Email" required autofocus>
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group position-relative has-icon-left mb-3">
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Kata Sandi" required>
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-check d-flex align-items-center mb-4">
                        <input class="form-check-input me-2 mt-0" type="checkbox" name="ingat_saya" value="1" id="ingatSaya">
                        <label class="form-check-label" for="ingatSaya" style="color: var(--cm-text-muted); font-size: 0.9rem;">Ingat saya</label>
                    </div>
                    <button class="btn btn-primary w-100 shadow-sm" type="submit">Masuk ke Dasbor</button>
                </form>
            </div>
            
            <!-- Sisi Kanan (Ilustrasi) -->
            <div class="auth-right">
                <div class="pattern-overlay"></div>
                <div class="glass-card">
                    <div class="mb-3 d-inline-block p-3 rounded-circle" style="background: rgba(255,255,255,0.15);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="16" y1="4" x2="16" y2="20"></line>
                            <line x1="8" y1="4" x2="8" y2="20"></line>
                            <line x1="4" y1="8" x2="20" y2="8"></line>
                            <line x1="4" y1="16" x2="20" y2="16"></line>
                        </svg>
                    </div>
                    <h4 class="fw-bold text-white mb-2">Manajemen Inventaris ROP</h4>
                    <p class="mb-0 small" style="color: rgba(255,255,255,0.9); line-height: 1.6;">
                        Pantau pergerakan barang, kelola stok gudang, dan dapatkan peringatan otomatis untuk pengadaan barang sebelum stok habis.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
