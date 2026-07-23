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

                <dt class="col-sm-3">Alamat</dt>
                <dd class="col-sm-9">{{ $pemasok->alamat ?? '-' }}</dd>
            </dl>
            <a href="{{ route('pemasok.edit', $pemasok) }}" class="btn btn-primary btn-sm">Edit</a>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-box-seam me-2"></i>Barang dari pemasok ini</span>
            <span class="badge bg-primary rounded-pill">{{ $pemasok->daftarBarang->count() }} Barang</span>
        </div>
        <div class="card-body p-0">
            @if($pemasok->daftarBarang->isEmpty())
                <div class="text-center py-4">
                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                    <p class="mt-2 text-muted">Belum ada barang dari pemasok ini</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Lead Time</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemasok->daftarBarang as $b)
                                <tr>
                                    <td><a href="{{ route('barang.edit', $b) }}">{{ $b->nama_barang }}</a></td>
                                    <td class="{{ $b->stok_saat_ini <= 0 ? 'text-danger fw-bold' : '' }}">{{ $b->stok_saat_ini }} {{ $b->satuan }}</td>
                                    <td>{{ $b->lead_time_hari }} Hari{{ $b->lead_time_menit > 0 ? ' ' . $b->lead_time_menit . ' Menit' : '' }}</td>
                                    <td>
                                        @if($b->status_barang === 'Aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('transaksi.create', ['jenis' => 'Masuk', 'id_barang' => $b->id_barang]) }}" class="btn btn-sm btn-success">
                                            <i class="bi bi-plus-lg"></i> Masuk
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
