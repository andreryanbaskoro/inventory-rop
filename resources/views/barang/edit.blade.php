@extends('layouts.app')

@section('judul_halaman', 'Edit Barang')
@section('subjudul', $barang->nama_barang)

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('barang.update', $barang) }}" method="post" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-4">
                    <label class="form-label">Pemasok</label>
                    <select name="id_pemasok" class="form-select @error('id_pemasok') is-invalid @enderror" required>
                        @foreach ($daftarPemasok as $p)
                            <option value="{{ $p->id_pemasok }}"
                                @selected(old('id_pemasok', $barang->id_pemasok) == $p->id_pemasok)>{{ $p->nama_pemasok }}</option>
                        @endforeach
                    </select>
                    @error('id_pemasok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}"
                        class="form-control @error('nama_barang') is-invalid @enderror" required>
                    @error('nama_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Lead Time</label>
                    <div class="card bg-light-secondary border-0 mb-0">
                        <div class="card-body p-2">
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label text-secondary small fw-bold mb-0">Hari</label>
                                    <input type="number" id="inputHari" name="lead_time_hari" value="{{ old('lead_time_hari', $barang->lead_time_hari) }}"
                                        class="form-control form-control-sm" min="0" placeholder="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-secondary small fw-bold mb-0">Menit</label>
                                    <input type="number" id="inputMenit" name="lead_time_menit" value="{{ old('lead_time_menit', $barang->lead_time_menit) }}"
                                        class="form-control form-control-sm" min="0" placeholder="0">
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill btn-cepat py-0 px-2" style="font-size: 0.75rem;" data-hari="0" data-menit="15">15 Menit</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill btn-cepat py-0 px-2" style="font-size: 0.75rem;" data-hari="0" data-menit="30">30 Menit</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill btn-cepat py-0 px-2" style="font-size: 0.75rem;" data-hari="0" data-menit="60">1 Jam</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill btn-cepat py-0 px-2" style="font-size: 0.75rem;" data-hari="1" data-menit="0">1 Hari</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill btn-cepat py-0 px-2" style="font-size: 0.75rem;" data-hari="3" data-menit="0">3 Hari</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill btn-cepat py-0 px-2" style="font-size: 0.75rem;" data-hari="7" data-menit="0">1 Minggu</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card bg-light-secondary border-0 mt-2 mb-3">
                        <div class="card-body p-3">
                            <h6 class="mb-2 text-brand"><i class="bi bi-box-seam me-2"></i>Pengaturan Satuan Barang</h6>
                            <p class="text-muted small mb-3">
                                Tentukan satuan dasar/terkecil (wajib) dan satuan besar opsional (misal: Karton) jika Anda membeli barang ini dalam jumlah besar.
                            </p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold">Satuan Dasar/Terkecil <span class="text-danger">*</span></label>
                                    <input type="text" name="satuan" value="{{ old('satuan', $barang->satuan) }}" class="form-control" placeholder="Contoh: PCS">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold">Satuan Besar (Opsional)</label>
                                    <input type="text" name="satuan_besar" value="{{ old('satuan_besar', $barang->satuan_besar) }}" class="form-control" placeholder="Contoh: KARTON">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold">Isi per Satuan Besar</label>
                                    <input type="number" name="isi_per_satuan_besar" value="{{ old('isi_per_satuan_besar', $barang->isi_per_satuan_besar) }}" class="form-control" min="1" placeholder="Contoh: 24">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Status</label>
                    <select name="status_barang" class="form-select">
                        <option value="Aktif" @selected(old('status_barang', $barang->status_barang) == 'Aktif')>Aktif
                        </option>
                        <option value="Nonaktif" @selected(old('status_barang', $barang->status_barang) == 'Nonaktif')>
                            Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harga beli</label>
                    <input type="number" step="0.01" name="harga_beli" value="{{ old('harga_beli', $barang->harga_beli) }}"
                        class="form-control @error('harga_beli') is-invalid @enderror" min="0" required>
                    @error('harga_beli')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harga jual</label>
                    <input type="number" step="0.01" name="harga_jual" value="{{ old('harga_jual', $barang->harga_jual) }}"
                        class="form-control @error('harga_jual') is-invalid @enderror" min="0" required>
                    @error('harga_jual')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Biaya pesan (S)</label>
                    <input type="number" step="0.01" name="biaya_pesan" id="inputBiayaPesan" value="{{ old('biaya_pesan', $barang->biaya_pesan) }}"
                        class="form-control" min="0">
                    <div class="form-text small text-muted" id="helpBiayaPesan">Biarkan 0 untuk otomatis estimasi: <strong class="text-primary">Rp 20.000</strong></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Biaya simpan per unit per tahun (H)</label>
                    <input type="number" step="0.01" name="biaya_simpan" id="inputBiayaSimpan"
                        value="{{ old('biaya_simpan', $barang->biaya_simpan) }}" class="form-control" min="0">
                    <div class="form-text small text-muted" id="helpBiayaSimpan">Biarkan 0 untuk otomatis estimasi: <strong class="text-primary">Rp 2.000</strong></div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
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

        // Fitur Pintar Estimasi EOQ
        const inputHargaBeli = document.querySelector('input[name="harga_beli"]');
        const helpBiayaPesan = document.getElementById('helpBiayaPesan');
        const helpBiayaSimpan = document.getElementById('helpBiayaSimpan');

        function updateEstimasi() {
            let harga = parseFloat(inputHargaBeli.value) || 0;
            
            let estimasiPesan = harga * 0.05;
            if (estimasiPesan <= 0) estimasiPesan = 20000;
            
            let estimasiSimpan = harga * 0.20;
            if (estimasiSimpan <= 0) estimasiSimpan = 2000;

            helpBiayaPesan.innerHTML = `Biarkan 0 untuk otomatis estimasi: <strong class="text-primary">Rp ${estimasiPesan.toLocaleString('id-ID')} (5% Harga Beli)</strong> <button type="button" class="btn btn-sm btn-link p-0 ms-1 text-decoration-none" onclick="document.getElementById('inputBiayaPesan').value = ${estimasiPesan}">[Isi ke Form]</button>`;
            helpBiayaSimpan.innerHTML = `Biarkan 0 untuk otomatis estimasi: <strong class="text-primary">Rp ${estimasiSimpan.toLocaleString('id-ID')} (20% Harga Beli)</strong> <button type="button" class="btn btn-sm btn-link p-0 ms-1 text-decoration-none" onclick="document.getElementById('inputBiayaSimpan').value = ${estimasiSimpan}">[Isi ke Form]</button>`;
        }

        if (inputHargaBeli) {
            inputHargaBeli.addEventListener('input', updateEstimasi);
            updateEstimasi();
        }
    });
</script>
@endpush
