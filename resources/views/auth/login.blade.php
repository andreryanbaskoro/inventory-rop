@php($asetMazer = asset('mazer-1.0.0/dist'))
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Cahaya Mulya Mart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $asetMazer }}/assets/css/bootstrap.css">
    <link rel="stylesheet" href="{{ $asetMazer }}/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ $asetMazer }}/assets/css/app.css">
    <link rel="stylesheet" href="{{ $asetMazer }}/assets/css/pages/auth.css">
    <link rel="stylesheet" href="{{ asset('css/premium.css') }}">
    <style>
        #auth {
            background-color: var(--cm-surface);
        }
        #auth-left {
            padding: 4rem !important;
        }
        .auth-logo-box {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--cm-primary), var(--cm-accent));
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }
        .auth-logo-box svg {
            width: 28px;
            height: 28px;
        }
        .right-illustration {
            background: linear-gradient(135deg, var(--cm-primary-dark), var(--cm-primary));
            position: relative;
            overflow: hidden;
            border-top-left-radius: 40px;
            border-bottom-left-radius: 40px;
            margin: 1.5rem 1.5rem 1.5rem 0;
            box-shadow: -10px 0 30px rgba(0,0,0,0.1);
        }
        .pattern-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0);
            background-size: 32px 32px;
            opacity: 0.8;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 3rem;
            color: #fff;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div id="auth">
        <div class="row h-100 m-0">
            <div class="col-lg-5 col-12 d-flex align-items-center justify-content-center">
                <div id="auth-left" class="w-100" style="max-width: 550px;">
                    <div class="mb-5">
                        <a href="{{ route('login') }}" class="text-decoration-none d-inline-flex align-items-center gap-3">
                            <div class="auth-logo-box">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.02em;">Cahaya Mulya Mart</h4>
                                <span class="d-block small" style="color: var(--cm-text-muted);">Sistem Inventaris ROP</span>
                            </div>
                        </a>
                    </div>
                    <h2 class="auth-title fw-bold mb-2">Selamat Datang</h2>
                    <p class="auth-subtitle mb-5" style="color: var(--cm-text-muted);">Silakan masuk ke akun Anda untuk melanjutkan.</p>

                    <form action="{{ route('login.proses') }}" method="post">
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control form-control-xl @error('email') is-invalid @enderror"
                                placeholder="Email" required autofocus>
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" name="password"
                                class="form-control form-control-xl @error('password') is-invalid @enderror"
                                placeholder="Kata sandi" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check form-check-lg d-flex align-items-center mb-5">
                            <input class="form-check-input me-2 mt-0" type="checkbox" name="ingat_saya" value="1"
                                id="ingatSaya">
                            <label class="form-check-label" for="ingatSaya" style="color: var(--cm-text-muted); padding-top: 2px;">Ingat saya</label>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg" type="submit">Masuk ke Dasbor</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-flex p-0">
                <div class="w-100 h-100 right-illustration d-flex align-items-center justify-content-center">
                    <div class="pattern-overlay"></div>
                    <div class="glass-card position-relative z-index-1 text-center" style="max-width: 480px;">
                        <div class="mb-4 d-inline-block p-3 rounded-circle" style="background: rgba(255,255,255,0.15);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="16" y1="4" x2="16" y2="20"></line>
                                <line x1="8" y1="4" x2="8" y2="20"></line>
                                <line x1="4" y1="8" x2="20" y2="8"></line>
                                <line x1="4" y1="16" x2="20" y2="16"></line>
                            </svg>
                        </div>
                        <h3 class="fw-bold text-white mb-3">Manajemen Inventaris ROP & EOQ</h3>
                        <p class="mb-0" style="color: rgba(255,255,255,0.8); line-height: 1.6;">
                            Pantau pergerakan barang, kelola stok gudang, dan dapatkan peringatan otomatis untuk pengadaan barang sebelum stok habis.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
