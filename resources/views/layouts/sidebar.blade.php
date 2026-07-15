@php($asetMazer = asset('mazer-1.0.0/dist'))
@php($pengguna = auth()->user())
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header cm-sidebar-header">
            <div class="d-flex justify-content-between align-items-start">
                <a href="{{ route('dashboard.index') }}" class="cm-brand text-decoration-none d-flex align-items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Cahaya Mulya Mart" style="height: 40px; width: auto; border-radius: 8px;">
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
