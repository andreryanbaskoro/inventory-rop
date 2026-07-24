@php($asetMazer = asset('mazer-1.0.0/dist'))
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul_halaman', 'Dashboard') — Cahaya Mulya Mart</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $asetMazer }}/assets/css/bootstrap.css">
    <link rel="stylesheet" href="{{ $asetMazer }}/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="{{ $asetMazer }}/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ $asetMazer }}/assets/css/app.css">
    <link rel="stylesheet" href="{{ asset('css/premium.css') }}">
    @stack('styles')
</head>

<body>
    <div id="app">
        @include('layouts.sidebar')

        <div id="main">
            @include('layouts.navbar')

            <div class="page-heading">
                @hasSection('breadcrumb')
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>@yield('judul_halaman')</h3>
                                <p class="text-subtitle text-muted">@yield('subjudul')</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                @yield('breadcrumb')
                            </div>
                        </div>
                    </div>
                @else
                    <div class="page-title">
                        <h3>@yield('judul_halaman')</h3>
                        <p class="text-subtitle text-muted">@yield('subjudul')</p>
                    </div>
                @endif

                <section class="section">
                    @if (session('sukses'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('sukses') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                    @endif
                    @yield('konten')
                </section>
            </div>

            @include('layouts.footer')
        </div>
    </div>

    <script src="{{ $asetMazer }}/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="{{ $asetMazer }}/assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{ $asetMazer }}/assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (!(form instanceof HTMLFormElement) || !form.classList.contains('form-hapus')) {
                return;
            }
            e.preventDefault();
            const nama = form.dataset.nama || 'data ini';
            Swal.fire({
                title: 'Konfirmasi hapus',
                text: 'Anda akan menghapus ' + nama,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1e3a5f',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
    <script>
        // Initialize tooltips safely (avoid double init)
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                if (!bootstrap.Tooltip.getInstance(tooltipTriggerEl)) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                }
            });
        });
    </script>
    @stack('scripts')
    @if (session('sukses'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('sukses')),
                timer: 2200,
                showConfirmButton: false
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: @json(session('error'))
            });
        </script>
    @endif
</body>

</html>