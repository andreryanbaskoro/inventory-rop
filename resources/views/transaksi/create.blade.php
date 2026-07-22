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
                            <option value="{{ $b->id_barang }}" 
                                data-satuan="{{ $b->satuan }}" 
                                data-satuan-besar="{{ $b->satuan_besar }}"
                                @selected(old('id_barang', request('id_barang')) == $b->id_barang)>
                                {{ $b->nama_barang }} (stok {{ $b->stok_saat_ini }} {{ $b->satuan }})
                            </option>
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
                <div class="col-md-6">
                    <label class="form-label">Jumlah & Satuan</label>
                    <div class="input-group">
                        <input type="number" name="jumlah_input" value="{{ old('jumlah_input', 1) }}"
                            class="form-control @error('jumlah_input') is-invalid @enderror" min="1" required>
                        <select name="satuan_input" id="satuanSelect" class="form-select @error('satuan_input') is-invalid @enderror" style="max-width: 150px;" required>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectBarang = document.querySelector('select[name="id_barang"]');
        const satuanSelect = document.getElementById('satuanSelect');
        const oldSatuan = "{{ old('satuan_input') }}";

        function updateSatuanOptions() {
            const selectedOption = selectBarang.options[selectBarang.selectedIndex];
            satuanSelect.innerHTML = ''; // clear options
            
            if (!selectedOption.value) {
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
            
            // If no old value but we just populated, select the first option by default
            if (!oldSatuan && satuanSelect.options.length > 0) {
                satuanSelect.selectedIndex = 0;
            }
        }

        selectBarang.addEventListener('change', updateSatuanOptions);
        
        // Run on page load for old input repopulation
        if(selectBarang.value) {
            updateSatuanOptions();
        }
    });
</script>
@endpush
