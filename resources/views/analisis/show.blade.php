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
<div class="row g-3 mb-4">
    <!-- Status Card -->
    <div class="col-12 col-lg-4 col-md-6">
        <div class="card cm-stat-card h-100 hover-lift">
            <div class="card-body d-flex align-items-center gap-3 py-4">
                @if($analisis['perlu_reorder'])
                    <div class="cm-stat-icon" style="background: #fee2e2; color: #dc2626; border-color: #fca5a5;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                @else
                    <div class="cm-stat-icon" style="background: #dcfce7; color: #16a34a; border-color: #86efac;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                @endif
                <div>
                    <div class="cm-stat-label">Status Inventaris</div>
                    <div class="cm-stat-value" style="font-size: 1.25rem; {{ $analisis['perlu_reorder'] ? 'color: #dc2626;' : 'color: #16a34a;' }}">
                        {{ $analisis['perlu_reorder'] ? 'PERLU REORDER' : 'STOK AMAN' }}
                    </div>
                    <span class="text-muted small">Stok Riil: {{ number_format($barang->stok_saat_ini ?? 0, 0, ',', '.') }} {{ $barang->satuan }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ROP Card -->
    <div class="col-12 col-lg-4 col-md-6">
        <div class="card cm-stat-card h-100 hover-lift">
            <div class="card-body d-flex align-items-center gap-3 py-4">
                <div class="cm-stat-icon" style="background: #dbeafe; color: #2563eb; border-color: #93c5fd;">
                    <i class="bi bi-bullseye"></i>
                </div>
                <div>
                    <div class="cm-stat-label">
                        Reorder Point (ROP)
                        <i class="bi bi-question-circle-fill ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Titik batas. Jika sisa stok mencapai angka ini, Anda harus segera pesan ke Pemasok!"></i>
                    </div>
                    <div class="cm-stat-value text-dark">{{ number_format(round($analisis['rop']), 0, ',', '.') }}</div>
                    <span class="text-muted small">Titik pemesanan kembali</span>
                </div>
            </div>
        </div>
    </div>

    <!-- EOQ Card -->
    <div class="col-12 col-lg-4 col-md-6">
        <div class="card cm-stat-card h-100 hover-lift">
            <div class="card-body d-flex align-items-center gap-3 py-4">
                <div class="cm-stat-icon" style="background: #cffafe; color: #0891b2; border-color: #67e8f9;">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="cm-stat-label">
                        Economic Order Qty
                        <i class="bi bi-question-circle-fill ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Jumlah pemesanan paling ideal & hemat biaya (biaya pesan + simpan)."></i>
                    </div>
                    <div class="cm-stat-value {{ $analisis['eoq'] === null ? 'text-muted' : 'text-dark' }}">
                        {{ $analisis['eoq'] !== null ? number_format(round($analisis['eoq']), 0, ',', '.') : 'N/A' }}
                    </div>
                    <span class="text-muted small">Rekomendasi jumlah pesanan</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. DETAIL PERHITUNGAN -->
<div class="card mb-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-table me-2 text-primary"></i>Rincian Analisis ROP & EOQ</h5>
        
        <form method="GET" action="{{ route('analisis.show', $barang) }}" class="d-flex align-items-center bg-light p-1 rounded-3">
            <label for="periode" class="me-2 fw-semibold text-muted small ps-2 text-nowrap"><i class="bi bi-calendar3 me-1"></i>Periode:</label>
            <select name="periode" id="periode" class="form-select form-select-sm border-0 bg-transparent fw-bold" onchange="this.form.submit()" style="width: auto; cursor:pointer;">
                <option value="7" {{ $periodeHari == 7 ? 'selected' : '' }}>7 Hari (1 Minggu)</option>
                <option value="14" {{ $periodeHari == 14 ? 'selected' : '' }}>14 Hari (2 Minggu)</option>
                <option value="21" {{ $periodeHari == 21 ? 'selected' : '' }}>21 Hari (3 Minggu)</option>
                <option value="30" {{ $periodeHari == 30 ? 'selected' : '' }}>30 Hari (1 Bulan)</option>
                <option value="60" {{ $periodeHari == 60 ? 'selected' : '' }}>60 Hari (2 Bulan)</option>
                <option value="90" {{ $periodeHari == 90 ? 'selected' : '' }}>90 Hari (3 Bulan)</option>
                <option value="180" {{ $periodeHari == 180 ? 'selected' : '' }}>180 Hari (6 Bulan)</option>
                <option value="365" {{ $periodeHari == 365 ? 'selected' : '' }}>365 Hari (1 Tahun)</option>
            </select>
        </form>
    </div>
    
    <div class="card-body pt-3">
        
        <!-- Variabel Historis -->
        <h6 class="fw-bold mb-3 text-uppercase" style="color: var(--cm-text-muted); font-size: 0.8rem; letter-spacing: 0.5px;">Rincian Variabel & Hasil Perhitungan ({{ $periodeHari }} Hari Terakhir)</h6>
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-striped mb-0">
                <tbody class="align-middle">
                    <tr>
                        <td width="30%" class="fw-semibold">Total Pemakaian (<var>Total</var>)</td>
                        <td class="fw-bold text-dark">{{ number_format($analisis['total_keluar_periode'], 0, ',', '.') }} {{ $barang->satuan }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Pemakaian Rata-rata (<var>d<sub>avg</sub></var>)</td>
                        <td class="fw-bold text-dark">
                            {{ number_format($analisis['total_keluar_periode'], 0, ',', '.') }} {{ $barang->satuan }} per {{ $periodeHari }} Hari
                            
                            <div class="text-muted small fw-normal mt-1">
                                <i class="bi bi-arrow-right-short"></i> Dikonversi ke desimal: <strong>{{ str_replace('.', ',', round($analisis['pemakaian_rata_harian'], 4)) }} {{ $barang->satuan }}/hari</strong>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Pemakaian Maksimum (<var>d<sub>max</sub></var>)</td>
                        <td class="fw-bold text-dark">{{ $analisis['pemakaian_maks_harian'] }} {{ $barang->satuan }}/hari</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Lead Time (<var>L</var>)<br>
                            <small class="text-muted fw-normal">
                                Waktu tunggu pesanan tiba
                            </small>
                        </td>
                        <td class="fw-bold text-dark">
                            @if($analisis['lead_time_hari'] > 0)
                                {{ $analisis['lead_time_hari'] }} Hari
                            @endif
                            @if($analisis['lead_time_menit'] > 0)
                                {{ $analisis['lead_time_menit'] }} Menit
                            @endif
                            
                            @if($analisis['lead_time_menit'] > 0)
                            <div class="text-muted small fw-normal mt-1">
                                <i class="bi bi-arrow-right-short"></i> Dikonversi ke desimal: <strong>{{ str_replace('.', ',', round($analisis['lead_time_desimal'], 4)) }} Hari</strong>
                            </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Biaya Pesan (<var>S</var>)</td>
                        <td class="fw-bold text-dark">
                            Rp {{ number_format($analisis['biaya_pesan_dipakai'], 0, ',', '.') }}
                            @if($analisis['is_asumsi_s']) 
                                <span class="badge bg-warning text-dark ms-2 fw-normal" style="font-size: 0.65rem;">ASUMSI 5% DARI HARGA</span> 
                            @else
                                <span class="badge bg-success ms-2 fw-normal" style="font-size: 0.65rem;">DIISI PENGGUNA</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Biaya Simpan (<var>H</var>)</td>
                        <td class="fw-bold text-dark">
                            Rp {{ number_format($analisis['biaya_simpan_dipakai'], 0, ',', '.') }} <span class="text-muted fw-normal" style="font-size:0.8rem;">/ unit / thn</span>
                            @if($analisis['is_asumsi_h']) 
                                <span class="badge bg-warning text-dark ms-2 fw-normal" style="font-size: 0.65rem;">ASUMSI 20% DARI HARGA</span> 
                            @else
                                <span class="badge bg-success ms-2 fw-normal" style="font-size: 0.65rem;">DIISI PENGGUNA</span>
                            @endif
                        </td>
                    </tr>
                    <tr style="background-color: #f8fafc;">
                        <td colspan="2" class="fw-bold text-center text-secondary py-3 border-top border-bottom">HASIL ANALISIS</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">Safety Stock (SS)</td>
                        <td class="fw-bold text-primary" style="font-size: 1.1rem;">{{ number_format(round($analisis['safety_stock']), 0, ',', '.') }} <span class="fs-6 fw-normal text-dark">{{ $barang->satuan }}</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">Reorder Point (ROP)</td>
                        <td class="fw-bold text-danger" style="font-size: 1.1rem;">{{ number_format(round($analisis['rop']), 0, ',', '.') }} <span class="fs-6 fw-normal text-dark">{{ $barang->satuan }}</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">Economic Order Qty (EOQ)</td>
                        <td class="fw-bold text-success" style="font-size: 1.1rem;">{{ $analisis['eoq'] !== null ? number_format(round($analisis['eoq']), 0, ',', '.') : 'N/A' }} <span class="fs-6 fw-normal text-dark">{{ $barang->satuan }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>



    </div>
</div>
@endsection
