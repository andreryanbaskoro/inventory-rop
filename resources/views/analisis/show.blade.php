@extends('layouts.app')

@section('judul_halaman', 'Rincian Analisis ROP & EOQ')
@section('subjudul', $barang->nama_barang)

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('analisis.index') }}">Analisis</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $barang->nama_barang }}</li>
        </ol>
    </nav>
@endsection

@section('konten')
<!-- 1. HEADER WIDGETS -->
<div class="row">
    <!-- Status Card -->
    <div class="col-12 col-lg-4 col-md-6">
        <div class="card {{ $analisis['perlu_reorder'] ? 'bg-danger' : 'bg-success' }}">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-3 d-flex justify-content-start">
                        <div class="stats-icon white mb-2">
                            <i class="bi {{ $analisis['perlu_reorder'] ? 'bi-exclamation-triangle-fill' : 'bi-shield-check-fill' }} text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-9">
                        <h6 class="text-white font-semibold">Status Inventaris</h6>
                        <h6 class="font-extrabold text-white mb-0">{{ $analisis['perlu_reorder'] ? 'PERLU REORDER' : 'STOK AMAN' }}</h6>
                        <small class="text-white mt-1 d-block">Stok Riil: {{ number_format($barang->stok_saat_ini ?? 0, 0, ',', '.') }} {{ $barang->satuan }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ROP Card -->
    <div class="col-12 col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-3 d-flex justify-content-start">
                        <div class="stats-icon blue mb-2">
                            <i class="bi bi-bullseye text-primary fs-3"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-9">
                        <h6 class="text-muted font-semibold">Reorder Point (ROP)</h6>
                        <h6 class="font-extrabold mb-0">{{ number_format($analisis['rop'], 2, ',', '.') }}</h6>
                        <small class="text-muted mt-1 d-block">Titik pemesanan kembali</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EOQ Card -->
    <div class="col-12 col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-3 d-flex justify-content-start">
                        <div class="stats-icon purple mb-2">
                            <i class="bi bi-box-seam text-info fs-3"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-9">
                        <h6 class="text-muted font-semibold">Economic Order Qty</h6>
                        @if($analisis['eoq'] !== null)
                            <h6 class="font-extrabold mb-0">{{ number_format($analisis['eoq'], 2, ',', '.') }}</h6>
                        @else
                            <h6 class="font-extrabold text-muted mb-0">N/A</h6>
                        @endif
                        <small class="text-muted mt-1 d-block">Rekomendasi jumlah pesanan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. DETAIL PERHITUNGAN -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><i class="bi bi-calculator text-primary me-2"></i>Penjabaran Langkah Perhitungan</h5>
        
        <form method="GET" action="{{ route('analisis.show', $barang) }}" class="d-flex align-items-center">
            <label for="periode" class="me-2 fw-bold text-muted small text-nowrap">Periode Analisis:</label>
            <select name="periode" id="periode" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                <option value="30" {{ $periodeHari == 30 ? 'selected' : '' }}>30 Hari</option>
                <option value="60" {{ $periodeHari == 60 ? 'selected' : '' }}>60 Hari</option>
                <option value="90" {{ $periodeHari == 90 ? 'selected' : '' }}>90 Hari</option>
                <option value="180" {{ $periodeHari == 180 ? 'selected' : '' }}>180 Hari</option>
                <option value="365" {{ $periodeHari == 365 ? 'selected' : '' }}>365 Hari</option>
            </select>
        </form>
    </div>
    
    <div class="card-body">
        
        <!-- Variabel Historis -->
        <h6 class="font-bold mb-3 text-uppercase text-secondary">1. Variabel Historis ({{ $periodeHari }} Hari Terakhir)</h6>
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-striped mb-0">
                <tbody>
                    <tr>
                        <td width="30%"><strong>Total Pemakaian (<var>Total</var>)</strong></td>
                        <td>{{ number_format($analisis['total_keluar_periode'], 0, ',', '.') }} unit</td>
                    </tr>
                    <tr>
                        <td><strong>Pemakaian Rata-rata (<var>d<sub>avg</sub></var>)</strong><br><small class="text-muted"><var>Total</var> / {{ $periodeHari }}</small></td>
                        <td>{{ number_format($analisis['pemakaian_rata_harian'], 4, ',', '.') }} unit/hari</td>
                    </tr>
                    <tr>
                        <td><strong>Pemakaian Maksimum (<var>d<sub>max</sub></var>)</strong></td>
                        <td>{{ number_format($analisis['pemakaian_maks_harian'], 2, ',', '.') }} unit/hari</td>
                    </tr>
                    <tr>
                        <td><strong>Lead Time (<var>L</var>)</strong><br><small class="text-muted">{{ $analisis['lead_time_hari'] }} hari + {{ $analisis['lead_time_menit'] }} menit</small></td>
                        <td>{{ number_format($analisis['lead_time_desimal'], 4, ',', '.') }} hari</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row">
            <!-- SS & ROP (Kiri) -->
            <div class="col-lg-6">
                <h6 class="font-bold mb-3 text-uppercase text-secondary">2. Safety Stock & ROP</h6>
                
                <div class="alert alert-secondary">
                    <h6 class="alert-heading font-bold text-dark mb-2">Safety Stock (SS)</h6>
                    <div class="font-monospace small mb-2 text-dark">
                        <div><i class="bi bi-braces"></i> Rumus: SS = (<var>d<sub>max</sub></var> - <var>d<sub>avg</sub></var>) &times; <var>L</var></div>
                        <div><i class="bi bi-arrow-right-short"></i> Substitusi: SS = ({{ number_format($analisis['pemakaian_maks_harian'], 2, ',', '.') }} - {{ number_format($analisis['pemakaian_rata_harian'], 2, ',', '.') }}) &times; {{ number_format($analisis['lead_time_desimal'], 2, ',', '.') }}</div>
                    </div>
                    <span class="badge bg-primary fs-6">Hasil SS = {{ number_format($analisis['safety_stock'], 4, ',', '.') }}</span>
                </div>

                <div class="alert alert-secondary">
                    <h6 class="alert-heading font-bold text-dark mb-2">Reorder Point (ROP)</h6>
                    <div class="font-monospace small mb-2 text-dark">
                        <div><i class="bi bi-braces"></i> Rumus: ROP = (<var>d<sub>avg</sub></var> &times; <var>L</var>) + SS</div>
                        <div><i class="bi bi-arrow-right-short"></i> Substitusi: ROP = ({{ number_format($analisis['pemakaian_rata_harian'], 2, ',', '.') }} &times; {{ number_format($analisis['lead_time_desimal'], 2, ',', '.') }}) + {{ number_format($analisis['safety_stock'], 2, ',', '.') }}</div>
                    </div>
                    <span class="badge bg-danger fs-6">Hasil ROP = {{ number_format($analisis['rop'], 4, ',', '.') }}</span>
                </div>
            </div>

            <!-- EOQ (Kanan) -->
            <div class="col-lg-6">
                <h6 class="font-bold mb-3 text-uppercase text-secondary">3. Economic Order Quantity</h6>
                
                <div class="alert alert-secondary h-100">
                    <h6 class="alert-heading font-bold text-dark mb-3">Hasil EOQ</h6>
                    
                    <ul class="list-unstyled mb-3 small text-dark">
                        <li><strong>Biaya Pesan (S):</strong> Rp {{ number_format($barang->biaya_pesan, 0, ',', '.') }}</li>
                        <li><strong>Biaya Simpan (H):</strong> Rp {{ number_format($barang->biaya_simpan, 0, ',', '.') }} / unit / tahun</li>
                    </ul>

                    @if($analisis['eoq'] !== null)
                        <div class="font-monospace small mb-3 text-dark bg-white p-2 rounded">
                            <div class="mb-1"><i class="bi bi-braces"></i> Rumus: EOQ = &radic;((2 &times; D &times; S) / H)</div>
                            @php $estimasiTahunan = $analisis['pemakaian_rata_harian'] * 365; @endphp
                            <div class="mb-1"><i class="bi bi-info-circle"></i> D (Permintaan Tahunan) = <var>d<sub>avg</sub></var> &times; 365 = {{ number_format($estimasiTahunan, 2, ',', '.') }}</div>
                            <div><i class="bi bi-arrow-right-short"></i> Substitusi: EOQ = &radic;((2 &times; {{ number_format($estimasiTahunan, 2, ',', '.') }} &times; {{ number_format($barang->biaya_pesan, 0, '', '') }}) / {{ number_format($barang->biaya_simpan, 0, '', '') }})</div>
                        </div>
                        <span class="badge bg-success fs-6">Hasil EOQ = {{ number_format($analisis['eoq'], 4, ',', '.') }}</span>
                    @else
                        <div class="alert alert-warning mb-0 border-0">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                            EOQ tidak dapat dihitung. Pastikan Biaya Pesan dan Biaya Simpan diatur lebih dari 0.
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
