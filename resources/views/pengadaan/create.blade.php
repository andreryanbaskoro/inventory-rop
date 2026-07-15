@extends('layouts.app')

@section('judul_halaman', 'Tambah Pengadaan')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('pengadaan.index') }}">Pengadaan</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('pengadaan.store') }}" method="post" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Barang</label>
                    <select name="id_barang" class="form-select @error('id_barang') is-invalid @enderror" required>
                        <option value="">— Pilih —</option>
                        @foreach ($daftarBarang as $b)
                            <option value="{{ $b->id_barang }}" @selected(old('id_barang') == $b->id_barang)>
                                {{ $b->nama_barang }}</option>
                        @endforeach
                    </select>
                    @error('id_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pemasok</label>
                    <select name="id_pemasok" class="form-select @error('id_pemasok') is-invalid @enderror" required>
                        @foreach ($daftarPemasok as $p)
                            <option value="{{ $p->id_pemasok }}" @selected(old('id_pemasok') == $p->id_pemasok)>
                                {{ $p->nama_pemasok }}</option>
                        @endforeach
                    </select>
                    @error('id_pemasok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal pesan</label>
                    <input type="date" name="tanggal_pesan" value="{{ old('tanggal_pesan', now()->toDateString()) }}"
                        class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal datang (opsional)</label>
                    <input type="date" name="tanggal_datang" value="{{ old('tanggal_datang') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah pesan</label>
                    <input type="number" name="jumlah_pesan" value="{{ old('jumlah_pesan', 1) }}"
                        class="form-control @error('jumlah_pesan') is-invalid @enderror" min="1" required>
                    @error('jumlah_pesan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status_pengadaan" class="form-select">
                        <option value="Dipesan" @selected(old('status_pengadaan', 'Dipesan') === 'Dipesan')>Dipesan
                        </option>
                        <option value="Dikirim" @selected(old('status_pengadaan') === 'Dikirim')>Dikirim</option>
                        <option value="Selesai" @selected(old('status_pengadaan') === 'Selesai')>Selesai</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" rows="2" class="form-control">{{ old('catatan') }}</textarea>
                </div>
                <div class="col-12">
                    <p class="text-muted small mb-0">Status <strong>Selesai</strong> akan menambah stok barang dan
                        membuat transaksi masuk otomatis.</p>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a href="{{ route('pengadaan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
