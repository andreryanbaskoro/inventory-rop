@extends('layouts.app')

@section('judul_halaman', 'Analisis ROP & EOQ')
@section('subjudul', 'Berdasarkan transaksi keluar '.\App\Services\AnalisisRopEoqService::PERIODE_HARI.' hari terakhir')

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
                                <td><a href="{{ route('analisis.show', $b) }}" class="btn btn-sm btn-outline-primary">Rincian</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
