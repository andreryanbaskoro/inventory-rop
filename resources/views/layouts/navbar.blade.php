@php($pengguna = auth()->user())
<header class="cm-navbar mb-3">
    <nav class="navbar navbar-expand navbar-light cm-navbar-inner">
        <div class="container-fluid px-3">
            <a href="#" class="burger-btn d-block d-xl-none text-dark">
                <i class="bi bi-list fs-4"></i>
            </a>
            <span class="cm-navbar-title d-xl-none fw-bold text-brand ms-2">Cahaya Mulya Mart</span>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="navbar-nav ms-auto align-items-center gap-3">
                    <span class="text-muted small d-none d-md-inline">
                        <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('l, d F Y') }}
                    </span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="cm-user-avatar">{{ strtoupper(substr($pengguna->nama_pengguna, 0, 1)) }}</span>
                        <div class="d-none d-sm-block lh-sm">
                            <span class="d-block fw-semibold small">{{ $pengguna->nama_pengguna }}</span>
                            <span class="badge rounded-pill cm-badge-peran">{{ $pengguna->peran }}</span>
                        </div>
                    </div>
                    <form action="{{ route('keluar') }}" method="post" class="d-inline mb-0">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm px-3">
                            <i class="bi bi-box-arrow-right me-1"></i>Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>
