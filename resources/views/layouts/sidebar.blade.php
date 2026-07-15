@php($asetMazer = asset('mazer-1.0.0/dist'))
@php($pengguna = auth()->user())
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header cm-sidebar-header">
            <div class="d-flex justify-content-between align-items-start">
                <a href="{{ route('dashboard.index') }}" class="cm-brand text-decoration-none d-flex align-items-center gap-2">
                    <div class="auth-logo-box" style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,var(--cm-primary),var(--cm-accent));color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:22px;height:22px;">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <span>
                        <span class="cm-brand-title d-block fw-bold text-dark" style="font-size: 0.95rem; line-height: 1.2;">Cahaya Mulya</span>
                        <span class="cm-brand-sub d-block text-muted" style="font-size: 0.75rem;">Mart · Inventaris ROP</span>
                    </span>
                </a>
                <a href="#" class="sidebar-hide d-xl-none d-block text-muted"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu Utama</li>

                <li class="sidebar-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.index') }}" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('analisis.*') ? 'active' : '' }}">
                    <a href="{{ route('analisis.index') }}" class="sidebar-link">
                        <i class="bi bi-graph-up"></i>
                        <span>Analisis ROP & EOQ</span>
                    </a>
                </li>

                @if ($pengguna->isPemilik())
                    <li class="sidebar-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                        <a href="{{ route('laporan.index') }}" class="sidebar-link">
                            <i class="bi bi-file-earmark-bar-graph"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                @endif

                @if ($pengguna->isAdmin())
                    <li class="sidebar-title">Master Data</li>
                    <li class="sidebar-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                        <a href="{{ route('barang.index') }}" class="sidebar-link">
                            <i class="bi bi-box-seam"></i>
                            <span>Barang</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('pemasok.*') ? 'active' : '' }}">
                        <a href="{{ route('pemasok.index') }}" class="sidebar-link">
                            <i class="bi bi-truck"></i>
                            <span>Pemasok</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Transaksi</li>
                    <li class="sidebar-item {{ request()->routeIs('pengadaan.*') ? 'active' : '' }}">
                        <a href="{{ route('pengadaan.index') }}" class="sidebar-link">
                            <i class="bi bi-cart-check"></i>
                            <span>Pengadaan</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
                        <a href="{{ route('transaksi.index') }}" class="sidebar-link">
                            <i class="bi bi-arrow-left-right"></i>
                            <span>Barang Masuk / Keluar</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i class="bi bi-x"></i></button>
    </div>
</div>
