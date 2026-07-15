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
                    <div class="card bg-light-secondary border-0 mt-2 mb-0">
                        <div class="card-body p-3">
                            <h6 class="mb-2 text-brand"><i class="bi bi-clock-history me-2"></i>Pengaturan Waktu Tunggu (Lead Time)</h6>
                            <p class="text-muted small mb-3">
                                Seberapa lama pemasok ini rata-rata mengirimkan barang setelah dipesan? Anda bisa mengisi kombinasi hari dan menit. 
                                Misalnya jika selalu dikirim dalam setengah jam, isi <strong>0 Hari</strong> dan <strong>30 Menit</strong>.
                            </p>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-6 col-md-3">
                                    <label class="form-label text-secondary small fw-bold">Hari</label>
                                    <input type="number" id="inputHari" name="rata_lead_time" value="{{ old('rata_lead_time', $pemasok->rata_lead_time) }}"
                                        class="form-control form-control-sm" min="0" placeholder="0">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label text-secondary small fw-bold">Menit</label>
                                    <input type="number" id="inputMenit" name="rata_lead_time_menit" value="{{ old('rata_lead_time_menit', $pemasok->rata_lead_time_menit) }}"
                                        class="form-control form-control-sm" min="0" placeholder="30">
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span class="text-muted small">Pilihan Cepat:</span>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill btn-cepat" data-hari="0" data-menit="15">15 Menit</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill btn-cepat" data-hari="0" data-menit="30">30 Menit</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill btn-cepat" data-hari="0" data-menit="60">1 Jam</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill btn-cepat" data-hari="1" data-menit="0">1 Hari</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill btn-cepat" data-hari="3" data-menit="0">3 Hari</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill btn-cepat" data-hari="7" data-menit="0">1 Minggu</button>
                            </div>
                        </div>
                    </div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputHari = document.getElementById('inputHari');
        const inputMenit = document.getElementById('inputMenit');
        const btnCepat = document.querySelectorAll('.btn-cepat');

        btnCepat.forEach(btn => {
            btn.addEventListener('click', function() {
                inputHari.value = this.dataset.hari;
                inputMenit.value = this.dataset.menit;
            });
        });
    });
</script>
@endpush
