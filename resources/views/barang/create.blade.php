@extends('layouts.app')

@section('judul_halaman', 'Tambah Barang')
@section('subjudul', 'Isi formulir di bawah ini')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('barang.store') }}" method="post" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Pemasok</label>
                    <select name="id_pemasok" class="form-select @error('id_pemasok') is-invalid @enderror" required>
                        <option value="">— Pilih —</option>
                        @foreach ($daftarPemasok as $p)
                            <option value="{{ $p->id_pemasok }}" @selected(old('id_pemasok') == $p->id_pemasok)>
                                {{ $p->nama_pemasok }}</option>
                        @endforeach
                    </select>
                    @error('id_pemasok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                        class="form-control @error('nama_barang') is-invalid @enderror" required>
                    @error('nama_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan" value="{{ old('satuan', 'PCS') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stok awal</label>
                    <input type="number" name="stok_saat_ini" value="{{ old('stok_saat_ini', 0) }}"
                        class="form-control @error('stok_saat_ini') is-invalid @enderror" min="0" required>
                    @error('stok_saat_ini')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stok minimum</label>
                    <input type="number" name="stok_minimum" value="{{ old('stok_minimum', 0) }}"
                        class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status_barang" class="form-select">
                        <option value="Aktif" @selected(old('status_barang', 'Aktif') == 'Aktif')>Aktif</option>
                        <option value="Nonaktif" @selected(old('status_barang') == 'Nonaktif')>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harga beli</label>
                    <input type="number" step="0.01" name="harga_beli" value="{{ old('harga_beli', 0) }}"
                        class="form-control @error('harga_beli') is-invalid @enderror" min="0" required>
                    @error('harga_beli')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harga jual</label>
                    <input type="number" step="0.01" name="harga_jual" value="{{ old('harga_jual', 0) }}"
                        class="form-control @error('harga_jual') is-invalid @enderror" min="0" required>
                    @error('harga_jual')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Biaya pesan (S)</label>
                    <input type="number" step="0.01" name="biaya_pesan" value="{{ old('biaya_pesan', 0) }}"
                        class="form-control" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Biaya simpan per unit per tahun (H)</label>
                    <input type="number" step="0.01" name="biaya_simpan" value="{{ old('biaya_simpan', 0) }}"
                        class="form-control" min="0">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
