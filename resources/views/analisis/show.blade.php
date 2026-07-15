@extends('layouts.app')

@section('judul_halaman', 'Rincian Analisis')
@section('subjudul', $barang->nama_barang)

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('analisis.index') }}">Analisis</a></li>
            <li class="breadcrumb-item active">Rincian</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Ringkasan</div>
                <div class="card-body">
                    <p>Lead time pemasok: <strong>{{ $analisis['lead_time'] }} hari</strong></p>
                    <p>Total keluar ({{ $analisis['periode_hari'] }} hari): <strong>{{ $analisis['total_keluar_periode'] }}
                            unit</strong></p>
                    <p>Pemakaian rata-rata harian: <strong>{{ number_format($analisis['pemakaian_rata_harian'], 4, ',', '.') }}</strong>
                    </p>
                    <p>Pemakaian maksimum harian: <strong>{{ number_format($analisis['pemakaian_maks_harian'], 4, ',', '.') }}</strong>
                    </p>
                    <p>Safety stock: <strong>{{ number_format($analisis['safety_stock'], 4, ',', '.') }}</strong></p>
                    <p>ROP: <strong>{{ number_format($analisis['rop'], 4, ',', '.') }}</strong></p>
                    <p>EOQ:
                        @if ($analisis['eoq'] !== null)
                            <strong>{{ number_format($analisis['eoq'], 4, ',', '.') }}</strong>
                        @else
                            <span class="text-muted">Tidak dapat dihitung (pastikan biaya simpan H > 0 dan biaya pesan
                                S > 0)</span>
                        @endif
                    </p>
                    <p>Stok saat ini: <strong>{{ $barang->stok_saat_ini }}</strong></p>
                    @if ($analisis['perlu_reorder'])
                        <div class="alert alert-danger mb-0">Stok di atau di bawah ROP — <span
                                class="badge bg-danger">REORDER</span></div>
                    @else
                        <div class="alert alert-success mb-0">Stok di atas titik ROP.</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Parameter EOQ</div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>D (permintaan tahunan) = rata harian × 365 =
                            {{ number_format($analisis['pemakaian_rata_harian'] * 365, 2, ',', '.') }}</li>
                        <li>S (biaya pesan) = Rp {{ number_format($barang->biaya_pesan, 2, ',', '.') }}</li>
                        <li>H (biaya simpan per unit per tahun) = Rp {{ number_format($barang->biaya_simpan, 2, ',', '.') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
