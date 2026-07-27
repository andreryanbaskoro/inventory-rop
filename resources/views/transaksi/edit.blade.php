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
                <div class="col-md-3">
                    <label class="form-label fw-bold text-dark">Jenis Transaksi</label>
                    <select name="jenis" class="form-select border-2" required>
                        <option value="Masuk" @selected(old('jenis', $transaksi->jenis) === 'Masuk')>Masuk (Restock)</option>
                        <option value="Keluar" @selected(old('jenis', $transaksi->jenis) === 'Keluar')>Keluar (Terjual/Pakai)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-dark">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $transaksi->tanggal->toDateString()) }}"
                        class="form-control border-2 @error('tanggal') is-invalid @enderror" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark">Filter Pemasok <span class="text-muted fw-normal small">(Opsional: Saring daftar barang)</span></label>
                    <select id="filterPemasok" class="form-select border-2">
                        <option value="">— Semua Pemasok (Tampilkan Semua Barang) —</option>
                        @foreach ($daftarPemasok as $p)
                            <option value="{{ $p->id_pemasok }}" @selected(old('filter_pemasok', $transaksi->barang?->id_pemasok) == $p->id_pemasok)>
                                {{ $p->nama_pemasok }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark">Pilih Barang</label>
                    <select name="id_barang" id="selectBarang" class="form-select border-2 @error('id_barang') is-invalid @enderror" required>
                        @foreach ($daftarBarang as $b)
                            <option value="{{ $b->id_barang }}"
                                data-pemasok="{{ $b->id_pemasok }}"
                                data-satuan="{{ $b->satuan }}" 
                                data-satuan-besar="{{ $b->satuan_besar }}"
                                @selected(old('id_barang', $transaksi->id_barang) == $b->id_barang)>
                                {{ $b->nama_barang }} (stok {{ $b->stok_saat_ini }} {{ $b->satuan }})</option>
                        @endforeach
                    </select>
                    @error('id_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark">Jumlah & Satuan</label>
                    <div class="input-group">
                        <input type="number" name="jumlah_input" value="{{ old('jumlah_input', $transaksi->jumlah_input ?? $transaksi->jumlah) }}"
                            class="form-control border-2 @error('jumlah_input') is-invalid @enderror" min="1" required>
                        <select name="satuan_input" id="satuanSelect" class="form-select border-2 @error('satuan_input') is-invalid @enderror" style="max-width: 150px;" required>
                            <option value="">— Satuan —</option>
                        </select>
                    </div>
                    @error('jumlah_input')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('satuan_input')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold text-dark">Keterangan / Catatan</label>
                    <textarea name="keterangan" rows="2" class="form-control border-2">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                </div>
                <div class="col-12 mt-4">
                    <button class="btn btn-primary px-4" type="submit"><i class="bi bi-save me-1"></i> Perbarui</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterPemasok = document.getElementById('filterPemasok');
        const selectBarang = document.getElementById('selectBarang');
        const satuanSelect = document.getElementById('satuanSelect');
        const oldSatuan = "{{ old('satuan_input', $transaksi->satuan_input) }}";
        
        // Simpan salinan seluruh opsi barang asli
        const allBarangOptions = Array.from(selectBarang.options).map(opt => opt.cloneNode(true));

        function updateSatuanOptions() {
            const selectedOption = selectBarang.options[selectBarang.selectedIndex];
            satuanSelect.innerHTML = '';
            
            if (!selectedOption || !selectedOption.value) {
                satuanSelect.innerHTML = '<option value="">— Satuan —</option>';
                return;
            }

            const satuanDasar = selectedOption.dataset.satuan;
            const satuanBesar = selectedOption.dataset.satuanBesar;

            // Add Satuan Dasar
            if (satuanDasar) {
                const opt1 = document.createElement('option');
                opt1.value = satuanDasar;
                opt1.textContent = satuanDasar;
                if (oldSatuan === satuanDasar) opt1.selected = true;
                satuanSelect.appendChild(opt1);
            }

            // Add Satuan Besar if exists
            if (satuanBesar) {
                const opt2 = document.createElement('option');
                opt2.value = satuanBesar;
                opt2.textContent = satuanBesar;
                if (oldSatuan === satuanBesar) opt2.selected = true;
                satuanSelect.appendChild(opt2);
            }
            
            if (!oldSatuan && satuanSelect.options.length > 0) {
                satuanSelect.selectedIndex = 0;
            }
        }

        function filterBarangByPemasok() {
            const selectedPemasok = filterPemasok.value;
            const currentBarangVal = selectBarang.value;

            selectBarang.innerHTML = '';
            let valExists = false;

            allBarangOptions.forEach(opt => {
                if (!selectedPemasok || opt.dataset.pemasok === selectedPemasok) {
                    const newOpt = opt.cloneNode(true);
                    selectBarang.appendChild(newOpt);
                    if (newOpt.value === currentBarangVal) {
                        newOpt.selected = true;
                        valExists = true;
                    }
                }
            });

            if (selectBarang.options.length === 0) {
                const emptyOpt = document.createElement('option');
                emptyOpt.value = "";
                emptyOpt.textContent = "— Tidak ada barang dari pemasok ini —";
                selectBarang.appendChild(emptyOpt);
            } else if (!valExists) {
                selectBarang.selectedIndex = 0;
            }

            updateSatuanOptions();
        }

        filterPemasok.addEventListener('change', filterBarangByPemasok);
        selectBarang.addEventListener('change', updateSatuanOptions);
        
        // Initial load calculation
        if (filterPemasok.value) {
            filterBarangByPemasok();
        } else if (selectBarang.value) {
            updateSatuanOptions();
        }
    });
</script>
@endpush
