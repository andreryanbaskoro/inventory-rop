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
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                        class="form-control @error('nama_barang') is-invalid @enderror" required>
                    @error('nama_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">
                        Lead Time
                        <i class="bi bi-question-circle-fill text-primary ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Waktu tunggu sejak barang dipesan ke supplier hingga barang tiba di gudang."></i>
                    </label>
                    <div class="card bg-light-secondary border-0 mb-0">
                        <div class="card-body p-2">
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label text-secondary small fw-bold mb-0">Hari</label>
                                    <input type="number" id="inputHari" name="lead_time_hari" value="{{ old('lead_time_hari', 1) }}"
                                        class="form-control form-control-sm" min="0" placeholder="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-secondary small fw-bold mb-0">Menit</label>
                                    <input type="number" id="inputMenit" name="lead_time_menit" value="{{ old('lead_time_menit', 0) }}"
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
                            <h6 class="mb-2 text-brand">
                                <i class="bi bi-box-seam me-2"></i>Pengaturan Satuan Barang
                                <i class="bi bi-question-circle-fill text-primary ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Tentukan satuan dasar untuk perhitungan stok, dan satuan besar jika barang sering dibeli dalam dus/karton."></i>
                            </h6>
                            <div class="row g-2 mt-2">
                                <div class="col-md-12">
                                    <label class="form-label text-secondary small fw-bold">Barang ini disimpan/dijual secara eceran dalam satuan: <span class="text-danger">*</span></label>
                                    <input type="text" name="satuan" id="inputSatuanDasar" value="{{ old('satuan', 'PCS') }}" class="form-control border-2" placeholder="Contoh: PCS / BUNGKUS / KILOGRAM" required>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="form-check form-switch form-switch-lg">
                                        <input class="form-check-input" type="checkbox" role="switch" id="toggleSatuanBesar" style="cursor:pointer;" {{ old('satuan_besar') ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark ms-2 mt-1" for="toggleSatuanBesar" style="cursor:pointer;">
                                            Barang ini juga dibeli/disimpan dalam kemasan besar (seperti Dus/Karton)
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-12" id="containerSatuanBesar" style="display: {{ old('satuan_besar') ? 'block' : 'none' }};">
                                    <div class="p-3 bg-white border-2 rounded d-flex flex-wrap align-items-center gap-3 shadow-sm mt-1">
                                        <span class="fw-bold fs-5">1</span>
                                        <input type="text" name="satuan_besar" id="inputSatuanBesar" value="{{ old('satuan_besar') }}" class="form-control text-center fw-bold border-2 text-primary" style="width: 140px; text-transform: uppercase;" placeholder="Contoh: DUS">
                                        <span class="fw-bold fs-5 text-muted">berisi</span>
                                        <input type="number" name="isi_per_satuan_besar" id="inputIsi" value="{{ old('isi_per_satuan_besar') }}" class="form-control text-center fw-bold border-2 text-primary" style="width: 100px;" min="2" placeholder="24">
                                        <span class="fw-bold fs-5" id="labelSatuanEceran">PCS</span>
                                    </div>
                                    <small class="text-success mt-2 d-block fw-bold"><i class="bi bi-info-circle-fill me-1"></i>Sistem akan otomatis memecah satuan besar menjadi satuan eceran di dalam gudang.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="form-label">
                        Status
                        <i class="bi bi-question-circle-fill text-primary ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Aktif: Bisa ditransaksikan. Nonaktif: Diarsipkan dan berhenti dijual."></i>
                    </label>
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
                    <label class="form-label">
                        Biaya pesan (S)
                        <i class="bi bi-question-circle-fill text-primary ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Total biaya (ongkir, admin, parkir, dll) yang dikeluarkan SETIAP KALI Anda memesan barang ini ke Pemasok."></i>
                    </label>
                    <input type="number" step="0.01" name="biaya_pesan" id="inputBiayaPesan" value="{{ old('biaya_pesan', 0) }}"
                        class="form-control" min="0">
                    <div id="containerBiayaPesan"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">
                        Biaya simpan per unit per tahun (H)
                        <i class="bi bi-question-circle-fill text-primary ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Biaya perawatan, gudang, asuransi, dll untuk merawat 1 unit barang ini selama SETAHUN."></i>
                    </label>
                    <input type="number" step="0.01" name="biaya_simpan" id="inputBiayaSimpan" value="{{ old('biaya_simpan', 0) }}"
                        class="form-control" min="0">
                    <div id="containerBiayaSimpan"></div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputSatuanDasar = document.getElementById('inputSatuanDasar');
        const toggleSatuanBesar = document.getElementById('toggleSatuanBesar');
        const containerSatuanBesar = document.getElementById('containerSatuanBesar');
        const inputSatuanBesar = document.getElementById('inputSatuanBesar');
        const inputIsi = document.getElementById('inputIsi');
        const labelSatuanEceran = document.getElementById('labelSatuanEceran');

        function updateSatuanLabel() {
            labelSatuanEceran.textContent = inputSatuanDasar.value || '...';
        }

        inputSatuanDasar.addEventListener('input', updateSatuanLabel);
        updateSatuanLabel();

        toggleSatuanBesar.addEventListener('change', function() {
            if (this.checked) {
                containerSatuanBesar.style.display = 'block';
                inputSatuanBesar.required = true;
                inputIsi.required = true;
            } else {
                containerSatuanBesar.style.display = 'none';
                inputSatuanBesar.required = false;
                inputIsi.required = false;
                inputSatuanBesar.value = '';
                inputIsi.value = '';
            }
        });

        const inputHari = document.getElementById('inputHari');
        const inputMenit = document.getElementById('inputMenit');
        const btnCepat = document.querySelectorAll('.btn-cepat');

        btnCepat.forEach(btn => {
            btn.addEventListener('click', function() {
                inputHari.value = this.dataset.hari;
                inputMenit.value = this.dataset.menit;
            });
        });

        // Fitur Pintar Estimasi EOQ (UI Premium)
        const inputHargaBeli = document.querySelector('input[name="harga_beli"]');
        const containerPesan = document.getElementById('containerBiayaPesan');
        const containerSimpan = document.getElementById('containerBiayaSimpan');

        function updateEstimasi() {
            let harga = parseFloat(inputHargaBeli.value) || 0;
            
            let estimasiPesan = Math.ceil((harga * 0.05) / 100) * 100;
            if (estimasiPesan <= 0) estimasiPesan = 20000;
            
            let estimasiSimpan = Math.ceil((harga * 0.20) / 100) * 100;
            if (estimasiSimpan <= 0) estimasiSimpan = 2000;

            const renderUI = (nominal, persentase, inputId) => `
                <div class="mt-2 p-2 rounded d-flex justify-content-between align-items-center" style="background-color: #f8f9fa; border: 1px dashed #ced4da; transition: all 0.2s ease;">
                    <div class="small text-secondary" style="font-size: 0.8rem;">
                        <i class="bi bi-stars text-warning me-1"></i> 
                        Saran sistem: <strong class="text-dark">Rp ${nominal.toLocaleString('id-ID')}</strong> <span class="opacity-75">(${persentase}% harga)</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-light border shadow-sm rounded-pill px-3 py-1 text-primary fw-bold hover-lift" style="font-size: 0.7rem;" onclick="document.getElementById('${inputId}').value = ${nominal}">GUNAKAN</button>
                </div>
            `;

            containerPesan.innerHTML = renderUI(estimasiPesan, 5, 'inputBiayaPesan');
            containerSimpan.innerHTML = renderUI(estimasiSimpan, 20, 'inputBiayaSimpan');
        }

        if (inputHargaBeli) {
            inputHargaBeli.addEventListener('input', updateEstimasi);
            updateEstimasi();
        }
    });
</script>
@endpush
