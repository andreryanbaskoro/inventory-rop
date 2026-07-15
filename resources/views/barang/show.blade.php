@extends('layouts.app')

@section('judul_halaman', 'Detail Barang')
@section('subjudul', $barang->nama_barang)

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Informasi</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8">{{ $barang->id_barang }}</dd>
                        <dt class="col-sm-4">Pemasok</dt>
                        <dd class="col-sm-8">{{ $barang->pemasok?->nama_pemasok }}</dd>
                        <dt class="col-sm-4">Satuan Dasar</dt>
                        <dd class="col-sm-8">{{ $barang->satuan }}</dd>
                        @if($barang->satuan_besar)
                            <dt class="col-sm-4">Satuan Besar</dt>
                            <dd class="col-sm-8">{{ $barang->satuan_besar }} (1 {{ $barang->satuan_besar }} = {{ $barang->isi_per_satuan_besar }} {{ $barang->satuan }})</dd>
                        @endif
                        <dt class="col-sm-4">Stok</dt>
                        <dd class="col-sm-8">{{ $barang->stok_saat_ini }} {{ $barang->satuan }} (min {{ $barang->stok_minimum }} {{ $barang->satuan }})</dd>
                        <dt class="col-sm-4">Harga beli / jual</dt>
                        <dd class="col-sm-8">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }} /
                            Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</dd>
                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">{{ $barang->status_barang }}</dd>
                    </dl>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('barang.edit', $barang) }}" class="btn btn-primary btn-sm">Edit</a>
                    @endif
                    <a href="{{ route('analisis.show', $barang) }}" class="btn btn-outline-primary btn-sm">Analisis
                        ROP/EOQ</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Transaksi terbaru</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barang->daftarTransaksi as $t)
                                    <tr>
                                        <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                                        <td>{{ $t->jenis }}</td>
                                        <td>{{ $t->jumlah }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada transaksi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
