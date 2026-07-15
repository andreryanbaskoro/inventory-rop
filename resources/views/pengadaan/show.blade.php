@extends('layouts.app')

@section('judul_halaman', 'Detail Pengadaan')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('pengadaan.index') }}">Pengadaan</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $pengadaan->id_pengadaan }}</dd>
                <dt class="col-sm-3">Barang</dt>
                <dd class="col-sm-9"><a
                        href="{{ route('barang.show', $pengadaan->barang) }}">{{ $pengadaan->barang?->nama_barang }}</a>
                </dd>
                <dt class="col-sm-3">Pemasok</dt>
                <dd class="col-sm-9">{{ $pengadaan->pemasok?->nama_pemasok }}</dd>
                <dt class="col-sm-3">Tanggal pesan</dt>
                <dd class="col-sm-9">{{ $pengadaan->tanggal_pesan->format('d/m/Y') }}</dd>
                <dt class="col-sm-3">Tanggal datang</dt>
                <dd class="col-sm-9">{{ $pengadaan->tanggal_datang?->format('d/m/Y') ?? '-' }}</dd>
                <dt class="col-sm-3">Jumlah</dt>
                <dd class="col-sm-9">{{ $pengadaan->jumlah_pesan }}</dd>
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{{ $pengadaan->status_pengadaan }}</dd>
                <dt class="col-sm-3">Catatan</dt>
                <dd class="col-sm-9">{{ $pengadaan->catatan ?? '-' }}</dd>
            </dl>
            <a href="{{ route('pengadaan.edit', $pengadaan) }}" class="btn btn-primary btn-sm">Edit</a>
        </div>
    </div>
@endsection
