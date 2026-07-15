@extends('layouts.app')

@section('judul_halaman', 'Input Transaksi')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('transaksi.store') }}" method="post" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Barang</label>
                    <select name="id_barang" class="form-select @error('id_barang') is-invalid @enderror" required>
                        <option value="">— Pilih —</option>
                        @foreach ($daftarBarang as $b)
                            <option value="{{ $b->id_barang }}" @selected(old('id_barang') == $b->id_barang)>
                                {{ $b->nama_barang }} (stok {{ $b->stok_saat_ini }})</option>
                        @endforeach
                    </select>
                    @error('id_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}"
                        class="form-control @error('tanggal') is-invalid @enderror" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis</label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                        <option value="Masuk" @selected(old('jenis', $jenisAwal) === 'Masuk')>Masuk</option>
                        <option value="Keluar" @selected(old('jenis', $jenisAwal) === 'Keluar')>Keluar</option>
                    </select>
                    @error('jenis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}"
                        class="form-control @error('jumlah') is-invalid @enderror" min="1" required>
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="form-control">{{ old('keterangan') }}</textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
