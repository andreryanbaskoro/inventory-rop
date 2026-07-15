@extends('layouts.app')

@section('judul_halaman', 'Detail Transaksi')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $transaksi->id_transaksi }}</dd>
                <dt class="col-sm-3">Tanggal</dt>
                <dd class="col-sm-9">{{ $transaksi->tanggal->format('d/m/Y') }}</dd>
                <dt class="col-sm-3">Barang</dt>
                <dd class="col-sm-9"><a
                        href="{{ route('barang.show', $transaksi->barang) }}">{{ $transaksi->barang?->nama_barang }}</a>
                </dd>
                <dt class="col-sm-3">Jenis</dt>
                <dd class="col-sm-9">{{ $transaksi->jenis }}</dd>
                <dt class="col-sm-3">Jumlah</dt>
                <dd class="col-sm-9">{{ $transaksi->jumlah }}</dd>
                <dt class="col-sm-3">Keterangan</dt>
                <dd class="col-sm-9">{{ $transaksi->keterangan ?? '-' }}</dd>
            </dl>
        </div>
    </div>
@endsection
