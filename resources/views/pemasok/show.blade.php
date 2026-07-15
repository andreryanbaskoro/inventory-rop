@extends('layouts.app')

@section('judul_halaman', 'Detail Pemasok')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('pemasok.index') }}">Pemasok</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $pemasok->id_pemasok }}</dd>
                <dt class="col-sm-3">Nama</dt>
                <dd class="col-sm-9">{{ $pemasok->nama_pemasok }}</dd>
                <dt class="col-sm-3">Telepon</dt>
                <dd class="col-sm-9">{{ $pemasok->telepon ?? '-' }}</dd>
                <dt class="col-sm-3">Lead time</dt>
                <dd class="col-sm-9">{{ $pemasok->rata_lead_time }} Hari {{ $pemasok->rata_lead_time_menit }} Menit</dd>
                <dt class="col-sm-3">Alamat</dt>
                <dd class="col-sm-9">{{ $pemasok->alamat ?? '-' }}</dd>
            </dl>
            <a href="{{ route('pemasok.edit', $pemasok) }}" class="btn btn-primary btn-sm">Edit</a>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">Barang dari pemasok ini</div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemasok->daftarBarang as $b)
                        <tr>
                            <td>{{ $b->id_barang }}</td>
                            <td><a href="{{ route('barang.show', $b) }}">{{ $b->nama_barang }}</a></td>
                            <td>{{ $b->stok_saat_ini }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
