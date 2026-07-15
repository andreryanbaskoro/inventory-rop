@extends('layouts.app')

@section('judul_halaman', 'Edit Transaksi')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('transaksi.update', $transaksi) }}" method="post" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label class="form-label">Barang</label>
                    <select name="id_barang" class="form-select @error('id_barang') is-invalid @enderror" required>
                        @foreach ($daftarBarang as $b)
                            <option value="{{ $b->id_barang }}"
                                @selected(old('id_barang', $transaksi->id_barang) == $b->id_barang)>
                                {{ $b->nama_barang }} (stok {{ $b->stok_saat_ini }})</option>
                        @endforeach
                    </select>
                    @error('id_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $transaksi->tanggal->toDateString()) }}"
                        class="form-control @error('tanggal') is-invalid @enderror" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis</label>
                    <select name="jenis" class="form-select" required>
                        <option value="Masuk" @selected(old('jenis', $transaksi->jenis) === 'Masuk')>Masuk</option>
                        <option value="Keluar" @selected(old('jenis', $transaksi->jenis) === 'Keluar')>Keluar</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', $transaksi->jumlah) }}"
                        class="form-control @error('jumlah') is-invalid @enderror" min="1" required>
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="form-control">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Perbarui</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
