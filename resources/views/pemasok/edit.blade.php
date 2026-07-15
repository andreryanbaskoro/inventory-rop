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
                <div class="col-md-6">
                    <label class="form-label">Rata lead time (hari)</label>
                    <input type="number" name="rata_lead_time"
                        value="{{ old('rata_lead_time', $pemasok->rata_lead_time) }}" class="form-control" min="1">
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
@endsection
