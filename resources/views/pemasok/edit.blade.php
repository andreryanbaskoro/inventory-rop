@extends('layouts.app')

@section('judul_halaman', 'Edit Pemasok')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('pemasok.index') }}">Pemasok</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('pemasok.update', $pemasok) }}" method="post" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label class="form-label">Nama pemasok</label>
                    <input type="text" name="nama_pemasok" value="{{ old('nama_pemasok', $pemasok->nama_pemasok) }}"
                        class="form-control @error('nama_pemasok') is-invalid @enderror" required>
                    @error('nama_pemasok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $pemasok->telepon) }}"
                        class="form-control">
                </div>

                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" rows="3" class="form-control">{{ old('alamat', $pemasok->alamat) }}</textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Perbarui</button>
                    <a href="{{ route('pemasok.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @if($pemasok->daftarBarang && $pemasok->daftarBarang->count() > 0)
    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-box-seam me-2"></i>Barang dari pemasok ini</span>
            <span class="badge bg-primary rounded-pill">{{ $pemasok->daftarBarang->count() }} Barang</span>
        </div>
        <div class="card-body p-0">
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
        </div>
    </div>
    @endif
@endsection
