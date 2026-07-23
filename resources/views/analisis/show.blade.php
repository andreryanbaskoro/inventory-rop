@extends('layouts.app')

@section('judul_halaman', 'Rincian Analisis ROP & EOQ')
@section('subjudul', $barang->nama_barang)

@section('konten')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('analisis.index') }}" class="text-decoration-none">Analisis</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $barang->nama_barang }}</li>
    </ol>
</nav>

<!-- 1. HEADER WIDGETS (3 columns) -->
<div class="row mb-4">
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card shadow-sm h-100 border-0 {{ $analisis['perlu_reorder'] ? 'bg-danger-subtle' : 'bg-success-subtle' }}">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                @if($analisis['perlu_reorder'])
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-1 mb-2"></i>
                    <h5 class="card-title fw-bold text-danger mb-1">Status: Perlu Reorder</h5>
                @else
                    <i class="bi bi-check-circle-fill text-success fs-1 mb-2"></i>
                    <h5 class="card-title fw-bold text-success mb-1">Status: Aman</h5>
                @endif
                <p class="card-text text-muted mb-0">Stok Saat Ini: <strong>{{ number_format($barang->stok_saat_ini ?? $barang->stok ?? 0, 0, ',', '.') }}</strong></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card shadow-sm h-100 border-0 bg-primary-subtle">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                <i class="bi bi-bullseye text-primary fs-1 mb-2"></i>
                <h6 class="text-primary fw-semibold mb-1">Reorder Point (ROP)</h6>
                <h3 class="fw-bold text-primary mb-0">{{ number_format($analisis['rop'], 2, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100 border-0 bg-info-subtle">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                <i class="bi bi-box-seam text-info fs-1 mb-2"></i>
                <h6 class="text-info fw-semibold mb-1">Economic Order Qty (EOQ)</h6>
                @if($analisis['eoq'] !== null)
                    <h3 class="fw-bold text-info mb-0">{{ number_format($analisis['eoq'], 2, ',', '.') }}</h3>
                @else
                    <h5 class="fw-bold text-secondary mb-0">N/A</h5>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light border-bottom-0 pt-3 pb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-calculator me-2"></i>Penjabaran Perhitungan</h5>
        
        <form method="GET" action="{{ route('analisis.show', $barang->id) }}" class="d-flex align-items-center">
            <label for="periode" class="me-2 fw-semibold small text-muted text-nowrap">Periode Analisis:</label>
            <div class="input-group input-group-sm">
                <select name="periode" id="periode" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="30" {{ $periodeHari == 30 ? 'selected' : '' }}>30 Hari</option>
                    <option value="60" {{ $periodeHari == 60 ? 'selected' : '' }}>60 Hari</option>
                    <option value="90" {{ $periodeHari == 90 ? 'selected' : '' }}>90 Hari</option>
                    <option value="180" {{ $periodeHari == 180 ? 'selected' : '' }}>180 Hari</option>
                    <option value="365" {{ $periodeHari == 365 ? 'selected' : '' }}>365 Hari</option>
                </select>
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-arrow-repeat"></i></button>
            </div>
        </form>
    </div>
    <div class="card-body p-4">
        <!-- 2. SECTION 1: Variabel Historis -->
        <h6 class="fw-bold text-primary mb-3">1. Data Historis & Lead Time</h6>
        <p class="text-muted small mb-3">Berikut adalah variabel yang digunakan dari data {{ $periodeHari }} hari ke belakang.</p>
        <ul class="list-group list-group-flush mb-4 border rounded">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Total Pemakaian selama {{ $periodeHari }} hari (<var>Total</var>)</span>
                <span class="fw-semibold">{{ number_format($analisis['total_keluar_periode'], 2, ',', '.') }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Pemakaian Rata-rata Harian (<var>d<sub>avg</sub></var>) = <var>Total</var> / {{ $periodeHari }}</span>
                <span class="fw-semibold">{{ number_format($analisis['pemakaian_rata_harian'], 4, ',', '.') }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Pemakaian Maksimum Harian (<var>d<sub>max</sub></var>)</span>
                <span class="fw-semibold">{{ number_format($analisis['pemakaian_maks_harian'], 2, ',', '.') }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Lead Time (<var>L</var>) = {{ $analisis['lead_time_hari'] }} hari + {{ $analisis['lead_time_menit'] }} menit</span>
                <span class="fw-semibold">{{ number_format($analisis['lead_time_desimal'], 4, ',', '.') }} hari</span>
            </li>
        </ul>

        <!-- 3. SECTION 2: Safety Stock -->
        <h6 class="fw-bold text-primary mb-3">2. Perhitungan Safety Stock (SS)</h6>
        <div class="bg-light p-3 rounded mb-4 border border-secondary-subtle">
            <p class="font-monospace mb-2 text-dark"><i class="bi bi-braces"></i> <strong>Rumus:</strong> SS = (<var>d<sub>max</sub></var> - <var>d<sub>avg</sub></var>) &times; <var>L</var></p>
            <p class="font-monospace mb-2 text-muted"><strong>Substitusi:</strong> SS = ({{ number_format($analisis['pemakaian_maks_harian'], 4, ',', '.') }} - {{ number_format($analisis['pemakaian_rata_harian'], 4, ',', '.') }}) &times; {{ number_format($analisis['lead_time_desimal'], 4, ',', '.') }}</p>
            <hr class="my-2 text-secondary">
            <p class="font-monospace mb-0 fs-5 fw-bold text-primary">Hasil SS = {{ number_format($analisis['safety_stock'], 4, ',', '.') }}</p>
        </div>

        <!-- 4. SECTION 3: ROP -->
        <h6 class="fw-bold text-primary mb-3">3. Perhitungan Reorder Point (ROP)</h6>
        <div class="bg-light p-3 rounded mb-4 border border-secondary-subtle">
            <p class="font-monospace mb-2 text-dark"><i class="bi bi-braces"></i> <strong>Rumus:</strong> ROP = (<var>d<sub>avg</sub></var> &times; <var>L</var>) + SS</p>
            <p class="font-monospace mb-2 text-muted"><strong>Substitusi:</strong> ROP = ({{ number_format($analisis['pemakaian_rata_harian'], 4, ',', '.') }} &times; {{ number_format($analisis['lead_time_desimal'], 4, ',', '.') }}) + {{ number_format($analisis['safety_stock'], 4, ',', '.') }}</p>
            <hr class="my-2 text-secondary">
            <p class="font-monospace mb-0 fs-5 fw-bold text-danger">Hasil ROP = {{ number_format($analisis['rop'], 4, ',', '.') }}</p>
        </div>

        <!-- 5. SECTION 4: EOQ -->
        <h6 class="fw-bold text-primary mb-3">4. Perhitungan Economic Order Quantity (EOQ)</h6>
        <p class="text-muted small mb-2">
            <strong>Biaya Pesan (S):</strong> Rp {{ number_format($barang->biaya_pesan, 0, ',', '.') }} <br>
            <strong>Biaya Simpan (H):</strong> Rp {{ number_format($barang->biaya_simpan, 0, ',', '.') }} per unit/tahun
        </p>
        <div class="bg-light p-3 rounded mb-3 border border-secondary-subtle">
            @if($analisis['eoq'] !== null)
                <p class="font-monospace mb-2 text-dark"><i class="bi bi-braces"></i> <strong>Rumus:</strong> EOQ = &radic;((2 &times; D &times; S) / H)</p>
                @php
                    $estimasiTahunan = $analisis['pemakaian_rata_harian'] * 365;
                @endphp
                <p class="font-monospace mb-2 text-muted"><strong>Dimana D (Estimasi Tahunan)</strong> = <var>d<sub>avg</sub></var> &times; 365 = {{ number_format($estimasiTahunan, 4, ',', '.') }}</p>
                <p class="font-monospace mb-2 text-muted"><strong>Substitusi:</strong> EOQ = &radic;((2 &times; {{ number_format($estimasiTahunan, 4, ',', '.') }} &times; {{ number_format($barang->biaya_pesan, 0, '', '') }}) / {{ number_format($barang->biaya_simpan, 0, '', '') }})</p>
                <hr class="my-2 text-secondary">
                <p class="font-monospace mb-0 fs-5 fw-bold text-success">Hasil EOQ = {{ number_format($analisis['eoq'], 4, ',', '.') }}</p>
            @else
                <div class="d-flex align-items-center text-danger">
                    <i class="bi bi-exclamation-triangle fs-4 me-3"></i> 
                    <p class="mb-0 fw-semibold">EOQ tidak dapat dihitung karena Biaya Simpan (H) atau Biaya Pesan (S) belum diatur atau bernilai 0.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
