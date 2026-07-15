@extends('layouts.app')

@section('judul_halaman', 'Analisis ROP & EOQ')
@section('subjudul', 'Berdasarkan transaksi keluar '.$periodeHari.' hari terakhir')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Analisis</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Hasil Analisis</h5>
            <form action="{{ route('analisis.index') }}" method="GET" class="d-flex align-items-center gap-2">
                <label for="periode" class="text-muted small mb-0 text-nowrap">Filter Waktu:</label>
                <select name="periode" id="periode" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                    <option value="7" {{ $periodeHari == 7 ? 'selected' : '' }}>7 Hari (1 Minggu)</option>
                    <option value="14" {{ $periodeHari == 14 ? 'selected' : '' }}>14 Hari (2 Minggu)</option>
                    <option value="30" {{ $periodeHari == 30 ? 'selected' : '' }}>30 Hari (1 Bulan)</option>
                    <option value="90" {{ $periodeHari == 90 ? 'selected' : '' }}>90 Hari (3 Bulan)</option>
                    <option value="180" {{ $periodeHari == 180 ? 'selected' : '' }}>180 Hari (6 Bulan)</option>
                    <option value="365" {{ $periodeHari == 365 ? 'selected' : '' }}>365 Hari (1 Tahun)</option>
                </select>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Pemasok</th>
                            <th>Stok</th>
                            <th>ROP</th>
                            <th>EOQ</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarBarang as $baris)
                            @php($b = $baris['barang'])
                            @php($a = $baris['analisis'])
                            <tr>
                                <td>{{ $b->nama_barang }}</td>
                                <td>{{ $b->pemasok?->nama_pemasok }}</td>
                                <td>{{ $b->stok_saat_ini }}</td>
                                <td>{{ number_format($a['rop'], 2, ',', '.') }}</td>
                                <td>
                                    @if ($a['eoq'] !== null)
                                        {{ number_format($a['eoq'], 2, ',', '.') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($a['perlu_reorder'])
                                        <span class="badge bg-danger">REORDER</span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </td>
                                <td><a href="{{ route('analisis.show', ['barang' => $b->id_barang, 'periode' => $periodeHari]) }}" class="btn btn-sm btn-outline-primary">Rincian</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
