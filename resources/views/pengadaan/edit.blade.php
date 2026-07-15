@extends('layouts.app')

@section('judul_halaman', 'Edit Pengadaan')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('pengadaan.index') }}">Pengadaan</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('pengadaan.update', $pengadaan) }}" method="post" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label class="form-label">Barang</label>
                    <select name="id_barang" class="form-select" required>
                        @foreach ($daftarBarang as $b)
                            <option value="{{ $b->id_barang }}"
                                @selected(old('id_barang', $pengadaan->id_barang) == $b->id_barang)>{{ $b->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pemasok</label>
                    <select name="id_pemasok" class="form-select" required>
                        @foreach ($daftarPemasok as $p)
                            <option value="{{ $p->id_pemasok }}"
                                @selected(old('id_pemasok', $pengadaan->id_pemasok) == $p->id_pemasok)>{{ $p->nama_pemasok }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal pesan</label>
                    <input type="date" name="tanggal_pesan"
                        value="{{ old('tanggal_pesan', $pengadaan->tanggal_pesan->toDateString()) }}" class="form-control"
                        required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal datang</label>
                    <input type="date" name="tanggal_datang"
                        value="{{ old('tanggal_datang', $pengadaan->tanggal_datang?->toDateString()) }}"
                        class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah pesan</label>
                    <input type="number" name="jumlah_pesan" value="{{ old('jumlah_pesan', $pengadaan->jumlah_pesan) }}"
                        class="form-control" min="1" required
                        @if ($pengadaan->status_pengadaan === 'Selesai') readonly @endif>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status_pengadaan" class="form-select">
                        <option value="Dipesan" @selected(old('status_pengadaan', $pengadaan->status_pengadaan) === 'Dipesan')>Dipesan</option>
                        <option value="Dikirim" @selected(old('status_pengadaan', $pengadaan->status_pengadaan) === 'Dikirim')>Dikirim
                        </option>
                        <option value="Selesai" @selected(old('status_pengadaan', $pengadaan->status_pengadaan) === 'Selesai')>Selesai
                        </option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" rows="2" class="form-control">{{ old('catatan', $pengadaan->catatan) }}</textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Perbarui</button>
                    <a href="{{ route('pengadaan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
