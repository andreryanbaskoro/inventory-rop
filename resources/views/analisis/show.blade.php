@extends('layouts.app')

@section('judul_halaman', 'Rincian Analisis ROP & EOQ')
@section('subjudul', $barang->nama_barang)

@push('styles')
<style>
    .metric-card {
        transition: transform 0.2s ease-in-out;
        border-radius: 12px;
    }
    .metric-card:hover {
        transform: translateY(-3px);
    }
    .formula-box {
        background: #f8f9fa;
        border-left: 4px solid #0d6efd;
        border-radius: 8px;
    }
    .formula-box.ss { border-left-color: #0dcaf0; }
    .formula-box.rop { border-left-color: #dc3545; }
    .formula-box.eoq { border-left-color: #198754; }
    .var-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        height: 100%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
</style>
@endpush

@section('konten')
<!-- Breadcrumb & Periode Selector -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <nav aria-label="breadcrumb" class="mb-3 mb-md-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('analisis.index') }}" class="text-decoration-none">Analisis</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $barang->nama_barang }}</li>
        </ol>
    </nav>
    
    <div class="bg-white p-2 rounded shadow-sm border">
        <form method="GET" action="{{ route('analisis.show', $barang) }}" class="d-flex align-items-center mb-0">
            <label for="periode" class="me-3 fw-semibold text-muted mb-0"><i class="bi bi-calendar3 me-2"></i>Periode Analisis:</label>
            <select name="periode" id="periode" class="form-select form-select-sm border-0 bg-light fw-bold" onchange="this.form.submit()" style="cursor: pointer; width: 130px;">
                <option value="30" {{ $periodeHari == 30 ? 'selected' : '' }}>30 Hari</option>
                <option value="60" {{ $periodeHari == 60 ? 'selected' : '' }}>60 Hari</option>
                <option value="90" {{ $periodeHari == 90 ? 'selected' : '' }}>90 Hari</option>
                <option value="180" {{ $periodeHari == 180 ? 'selected' : '' }}>180 Hari</option>
                <option value="365" {{ $periodeHari == 365 ? 'selected' : '' }}>365 Hari</option>
            </select>
        </form>
    </div>
</div>

<!-- 1. HEADER WIDGETS -->
<div class="row g-3 mb-4">
    <!-- Status Card -->
    <div class="col-md-4">
        <div class="card metric-card border-0 shadow-sm h-100 {{ $analisis['perlu_reorder'] ? 'bg-danger text-white' : 'bg-success text-white' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 text-white-50 fw-semibold text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Status Inventaris</p>
                        @if($analisis['perlu_reorder'])
                            <h3 class="fw-bold mb-0">PERLU REORDER</h3>
                        @else
                            <h3 class="fw-bold mb-0">STOK AMAN</h3>
                        @endif
                    </div>
                    <div class="p-3 bg-white bg-opacity-25 rounded-3">
                        <i class="bi {{ $analisis['perlu_reorder'] ? 'bi-exclamation-triangle-fill' : 'bi-shield-check-fill' }} fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-light border-opacity-25">
                    <span class="fs-5 fw-semibold">{{ number_format($barang->stok_saat_ini ?? 0, 0, ',', '.') }}</span>
                    <span class="text-white-50 ms-1">unit tersedia di gudang</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ROP Card -->
    <div class="col-md-4">
        <div class="card metric-card border-0 shadow-sm h-100 bg-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 text-muted fw-semibold text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Reorder Point (ROP)</p>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($analisis['rop'], 2, ',', '.') }}</h3>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 rounded-3 text-primary">
                        <i class="bi bi-bullseye fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top text-muted small">
                    Pesan barang saat stok menyentuh angka ini
                </div>
            </div>
        </div>
    </div>

    <!-- EOQ Card -->
    <div class="col-md-4">
        <div class="card metric-card border-0 shadow-sm h-100 bg-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 text-muted fw-semibold text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Economic Order Qty</p>
                        @if($analisis['eoq'] !== null)
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($analisis['eoq'], 2, ',', '.') }}</h3>
                        @else
                            <h3 class="fw-bold text-muted mb-0">N/A</h3>
                        @endif
                    </div>
                    <div class="p-3 bg-info bg-opacity-10 rounded-3 text-info">
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top text-muted small">
                    Rekomendasi jumlah pesanan paling ekonomis
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. DETAIL PERHITUNGAN -->
<div class="card shadow-sm border-0 mb-4 rounded-4">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-calculator text-primary me-2"></i>Penjabaran Langkah Perhitungan</h5>
    </div>
    <div class="card-body p-4">
        
        <!-- Variabel Dasar -->
        <h6 class="fw-bold text-secondary mb-3 text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">1. Variabel Historis ({{ $periodeHari }} Hari Terakhir)</h6>
        <div class="row g-3 mb-5">
            <div class="col-6 col-md-3">
                <div class="var-card text-center">
                    <p class="text-muted small mb-1">Total Pemakaian</p>
                    <h5 class="fw-bold text-dark mb-0">{{ number_format($analisis['total_keluar_periode'], 0, ',', '.') }} <span class="fs-6 fw-normal text-muted">unit</span></h5>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="var-card text-center">
                    <p class="text-muted small mb-1">Pemakaian Rata-rata (<var>d<sub>avg</sub></var>)</p>
                    <h5 class="fw-bold text-primary mb-0">{{ number_format($analisis['pemakaian_rata_harian'], 2, ',', '.') }}</h5>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="var-card text-center">
                    <p class="text-muted small mb-1">Pemakaian Max (<var>d<sub>max</sub></var>)</p>
                    <h5 class="fw-bold text-danger mb-0">{{ number_format($analisis['pemakaian_maks_harian'], 2, ',', '.') }}</h5>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="var-card text-center">
                    <p class="text-muted small mb-1">Lead Time (<var>L</var>)</p>
                    <h5 class="fw-bold text-info mb-0">{{ number_format($analisis['lead_time_desimal'], 2, ',', '.') }} <span class="fs-6 fw-normal text-muted">hari</span></h5>
                </div>
            </div>
        </div>

        <!-- Perhitungan Matematis -->
        <div class="row g-4">
            <!-- SS & ROP (Kiri) -->
            <div class="col-lg-6">
                <h6 class="fw-bold text-secondary mb-3 text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">2. Safety Stock & ROP</h6>
                
                <div class="formula-box ss p-3 mb-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                        <span class="fw-bold text-dark">Safety Stock (SS)</span>
                        <span class="badge bg-info text-dark rounded-pill fs-6">{{ number_format($analisis['safety_stock'], 2, ',', '.') }}</span>
                    </div>
                    <div class="font-monospace small text-muted">
                        <div class="mb-1 text-dark"><i class="bi bi-braces text-info"></i> Rumus = (<var>d<sub>max</sub></var> - <var>d<sub>avg</sub></var>) &times; <var>L</var></div>
                        <div><i class="bi bi-arrow-return-right text-info"></i> Substitusi = ({{ number_format($analisis['pemakaian_maks_harian'], 2, ',', '.') }} - {{ number_format($analisis['pemakaian_rata_harian'], 2, ',', '.') }}) &times; {{ number_format($analisis['lead_time_desimal'], 2, ',', '.') }}</div>
                    </div>
                </div>

                <div class="formula-box rop p-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                        <span class="fw-bold text-dark">Reorder Point (ROP)</span>
                        <span class="badge bg-danger rounded-pill fs-6">{{ number_format($analisis['rop'], 2, ',', '.') }}</span>
                    </div>
                    <div class="font-monospace small text-muted">
                        <div class="mb-1 text-dark"><i class="bi bi-braces text-danger"></i> Rumus = (<var>d<sub>avg</sub></var> &times; <var>L</var>) + SS</div>
                        <div><i class="bi bi-arrow-return-right text-danger"></i> Substitusi = ({{ number_format($analisis['pemakaian_rata_harian'], 2, ',', '.') }} &times; {{ number_format($analisis['lead_time_desimal'], 2, ',', '.') }}) + {{ number_format($analisis['safety_stock'], 2, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <!-- EOQ (Kanan) -->
            <div class="col-lg-6">
                <h6 class="fw-bold text-secondary mb-3 text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">3. Economic Order Quantity</h6>
                
                <div class="formula-box eoq p-3 h-100 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <span class="fw-bold text-dark">Hasil EOQ</span>
                        @if($analisis['eoq'] !== null)
                            <span class="badge bg-success rounded-pill fs-6">{{ number_format($analisis['eoq'], 2, ',', '.') }}</span>
                        @else
                            <span class="badge bg-secondary rounded-pill fs-6">N/A</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <p class="mb-1 small"><strong>Biaya Pesan (S):</strong> Rp {{ number_format($barang->biaya_pesan, 0, ',', '.') }}</p>
                        <p class="mb-0 small"><strong>Biaya Simpan (H):</strong> Rp {{ number_format($barang->biaya_simpan, 0, ',', '.') }} <span class="text-muted">/ unit / tahun</span></p>
                    </div>

                    @if($analisis['eoq'] !== null)
                        <div class="font-monospace small text-muted bg-white p-3 rounded border">
                            <div class="mb-2 text-dark"><i class="bi bi-braces text-success"></i> Rumus = &radic;((2 &times; D &times; S) / H)</div>
                            @php $estimasiTahunan = $analisis['pemakaian_rata_harian'] * 365; @endphp
                            <div class="mb-2"><i class="bi bi-info-circle text-success"></i> D (Permintaan Tahunan) = <var>d<sub>avg</sub></var> &times; 365 = {{ number_format($estimasiTahunan, 2, ',', '.') }}</div>
                            <div><i class="bi bi-arrow-return-right text-success"></i> Substitusi = &radic;((2 &times; {{ number_format($estimasiTahunan, 2, ',', '.') }} &times; {{ number_format($barang->biaya_pesan, 0, '', '') }}) / {{ number_format($barang->biaya_simpan, 0, '', '') }})</div>
                        </div>
                    @else
                        <div class="alert alert-warning border-0 d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill text-warning fs-4 me-3"></i> 
                            <small class="text-dark">EOQ tidak dapat dihitung. Pastikan <strong>Biaya Pesan</strong> dan <strong>Biaya Simpan</strong> diatur &gt; 0 pada menu edit barang.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
