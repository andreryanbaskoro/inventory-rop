@extends('layouts.app')

@section('judul_halaman', 'Dashboard')
@section('subjudul', 'Ringkasan inventaris dan aktivitas transaksi')

@section('konten')
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card cm-stat-card h-100 hover-lift">
                <div class="card-body d-flex align-items-center gap-3 py-4">
                    <div class="cm-stat-icon"><i class="bi bi-box-seam"></i></div>
                    <div>
                        <div class="cm-stat-label">Barang Aktif</div>
                        <div class="cm-stat-value">{{ $totalBarang }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card cm-stat-card h-100 hover-lift">
                <div class="card-body d-flex align-items-center gap-3 py-4">
                    <div class="cm-stat-icon"><i class="bi bi-truck"></i></div>
                    <div>
                        <div class="cm-stat-label">Pemasok</div>
                        <div class="cm-stat-value">{{ $totalPemasok }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card cm-stat-card h-100 hover-lift">
                <div class="card-body d-flex align-items-center gap-3 py-4">
                    <div class="cm-stat-icon"><i class="bi bi-arrow-down-circle"></i></div>
                    <div>
                        <div class="cm-stat-label">Transaksi Masuk</div>
                        <div class="cm-stat-value">{{ $totalTransaksiMasuk }}</div>
                        <span class="text-muted small">({{ number_format($jumlahMasuk) }} unit)</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card cm-stat-card h-100 hover-lift">
                <div class="card-body d-flex align-items-center gap-3 py-4">
                    <div class="cm-stat-icon"><i class="bi bi-arrow-up-circle"></i></div>
                    <div>
                        <div class="cm-stat-label">Transaksi Keluar</div>
                        <div class="cm-stat-value">{{ $totalTransaksiKeluar }}</div>
                        <span class="text-muted small">({{ number_format($jumlahKeluar) }} unit)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($pengguna->isPemilik())
        <div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #1e3a5f, #2d5a87); border-radius: 16px;">
            <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3 text-white">
                <div>
                    <h5 class="mb-1 fw-bold text-white"><i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan Inventaris & Analisis Eksekutif</h5>
                    <p class="mb-0 small opacity-75">Ekspor Excel, PDF, atau cetak laporan stok dan transaksi untuk keputusan bisnis strategis.</p>
                </div>
                <a href="{{ route('laporan.index') }}" class="btn btn-light btn-sm fw-bold px-3 py-2 text-dark shadow-sm"><i class="bi bi-printer me-1"></i> Buka Laporan</a>
            </div>
        </div>
    @endif

    @if ($pengguna->isAdmin())
        <div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #0d6efd, #084298); border-radius: 16px;">
            <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3 text-white">
                <div>
                    <h5 class="mb-1 fw-bold text-white"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Akses Cepat Operasional Admin</h5>
                    <p class="mb-0 small opacity-75">Catat penerimaan barang masuk dari supplier atau mutasi penjualan harian secara langsung.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('transaksi.create', ['jenis' => 'Masuk']) }}" class="btn btn-light btn-sm fw-bold px-3 py-2 text-primary shadow-sm"><i class="bi bi-box-arrow-in-down me-1"></i> + Input Barang Masuk</a>
                    <a href="{{ route('transaksi.create', ['jenis' => 'Keluar']) }}" class="btn btn-warning btn-sm fw-bold px-3 py-2 text-dark shadow-sm"><i class="bi bi-box-arrow-up me-1"></i> - Input Barang Keluar</a>
                    <a href="{{ route('barang.create') }}" class="btn btn-outline-light btn-sm fw-semibold px-3 py-2"><i class="bi bi-plus-circle me-1"></i> + Master Barang</a>
                </div>
            </div>
        </div>
    @endif

    <div class="card mb-4 border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
        @if ($peringatanReorder->isNotEmpty())
            <div class="card-header bg-danger text-white py-3 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background-color: #dc3545 !important;">
                <h5 class="card-title text-white mb-0 fw-bold d-flex align-items-center">
                    <i class="bi bi-bell-fill fs-4 me-2"></i> 
                    Pemberitahuan Reorder Stok (ROP Triggered)
                    <span class="badge bg-white text-danger ms-3 fw-bold">{{ $peringatanReorder->count() }} Barang Perlu Perhatian</span>
                </h5>
                <a href="{{ route('analisis.index') }}" class="btn btn-sm btn-light text-danger fw-bold shadow-sm">
                    <i class="bi bi-graph-up me-1"></i> Lihat Semua Analisis
                </a>
            </div>
            <div class="card-body p-4" style="background-color: #fdf2f2;">
                <p class="text-dark mb-3">
                    <strong>Perhatian bagi Pemilik & Admin:</strong> Sistem mendeteksi barang-barang di bawah ini telah menembus batas <strong>Reorder Point (ROP)</strong> atau berada di bawah stok minimum. Disarankan segera melakukan pengadaan/order ke Pemasok untuk mencegah kehabisan stok (<em>stockout</em>).
                </p>
                <div class="table-responsive bg-white rounded shadow-sm border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Pemasok</th>
                                <th>Stok Saat Ini</th>
                                <th>Stok Minimum</th>
                                <th>Batas ROP</th>
                                <th>Saran Order (EOQ)</th>
                                <th>Status & Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($peringatanReorder as $item)
                                <tr>
                                    <td><span class="fw-medium text-secondary">{{ $item['barang']->id_barang }}</span></td>
                                    <td><strong>{{ $item['barang']->nama_barang }}</strong></td>
                                    <td>{{ $item['barang']->pemasok?->nama_pemasok ?? '-' }}</td>
                                    <td><span class="badge bg-danger fs-6">{{ $item['barang']->stok_saat_ini }} {{ $item['barang']->satuan }}</span></td>
                                    <td><span class="badge bg-light text-dark border">{{ $item['barang']->stok_minimum }} {{ $item['barang']->satuan }}</span></td>
                                    <td><strong class="text-danger">{{ ceil($item['rop']) }} {{ $item['barang']->satuan }}</strong></td>
                                    <td>
                                        @if ($item['eoq'] !== null)
                                            <span class="badge bg-success fs-6">{{ $item['eoq'] }} {{ $item['barang']->satuan }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('analisis.show', $item['barang']) }}" class="btn btn-sm btn-outline-info text-dark py-1" title="Rincian Analisis"><i class="bi bi-graph-up me-1"></i>Analisis</a>
                                            @if ($pengguna->isAdmin())
                                                <a href="{{ route('transaksi.create', ['jenis' => 'Masuk', 'id_barang' => $item['barang']->id_barang]) }}" class="btn btn-sm btn-primary py-1" title="Order Barang Masuk"><i class="bi bi-cart-plus me-1"></i>Order</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card-header bg-success text-white py-3 d-flex align-items-center justify-content-between" style="background-color: #198754 !important;">
                <h5 class="card-title text-white mb-0 fw-bold d-flex align-items-center">
                    <i class="bi bi-shield-check fs-4 me-2"></i> 
                    Pemberitahuan Status Inventaris: Stok Dalam Kondisi Aman
                </h5>
                <a href="{{ route('analisis.index') }}" class="btn btn-sm btn-light text-success fw-bold shadow-sm">
                    <i class="bi bi-graph-up me-1"></i> Analisis ROP & EOQ
                </a>
            </div>
            <div class="card-body p-4 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-success border" style="width: 55px; height: 55px; min-width: 55px;">
                        <i class="bi bi-check-circle-fill fs-2 text-success"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Seluruh Barang Mencegah Risiko Stockout!</h6>
                        <p class="text-muted mb-0 small">
                            Saat ini tidak ada barang yang berada di bawah titik <strong>Reorder Point (ROP)</strong> atau stok minimum. Seluruh persediaan toko terpantau aman dan cukup untuk memenuhi proyeksi permintaan pelanggan.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header py-3">
                    <i class="bi bi-bar-chart me-2"></i>Grafik Transaksi (6 bulan terakhir)
                </div>
                <div class="card-body">
                    <canvas id="grafikTransaksi" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header py-3">
                    <i class="bi bi-exclamation-circle me-2"></i>Stok di bawah minimum
                </div>
                <div class="card-body" style="max-height:360px;overflow:auto;">
                    @forelse ($barangStokKritis as $b)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2 gap-2">
                            <span class="small">{{ $b->nama_barang }}</span>
                            <span class="badge bg-warning text-dark">{{ $b->stok_saat_ini }} / {{ $b->stok_minimum }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0 small">Tidak ada barang di bawah stok minimum.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('grafikTransaksi');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($grafikLabel),
                datasets: [{
                        label: 'Masuk (unit)',
                        data: @json($grafikMasuk),
                        backgroundColor: 'rgba(30, 58, 95, 0.75)'
                    },
                    {
                        label: 'Keluar (unit)',
                        data: @json($grafikKeluar),
                        backgroundColor: 'rgba(108, 117, 125, 0.75)'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
