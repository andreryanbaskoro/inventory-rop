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
        <div class="card mb-4 border-0" style="background: linear-gradient(135deg, #1e3a5f, #2d5a87);">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3 text-white">
                <div>
                    <h5 class="mb-1 fw-bold"><i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan Inventaris</h5>
                    <p class="mb-0 small opacity-75">Ekspor Excel, PDF, atau cetak data stok, transaksi, dan pengadaan.</p>
                </div>
                <a href="{{ route('laporan.index') }}" class="btn btn-light btn-sm fw-semibold">Buka Laporan</a>
            </div>
        </div>
    @endif

    @if ($peringatanReorder->isNotEmpty())
        <div class="card border-0 mb-4 alert-soft-danger" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-0 pt-4 pb-2">
                <h5 class="card-title text-danger fw-bold mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Peringatan Reorder (ROP)</h5>
            </div>
            <div class="card-body">
                <ul class="mb-0 ps-3 text-danger">
                    @foreach ($peringatanReorder as $item)
                        <li class="mb-2">
                            <strong>{{ $item['barang']->nama_barang }}</strong> — stok
                            {{ $item['barang']->stok_saat_ini }}, ROP
                            {{ number_format($item['rop'], 2, ',', '.') }}
                            <span class="badge bg-danger">REORDER</span>
                            <a href="{{ route('analisis.show', $item['barang']) }}" class="ms-1">Detail</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

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
