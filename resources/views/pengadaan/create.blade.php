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
                            <option value="{{ $b->id_barang }}"
                                data-satuan="{{ $b->satuan }}" 
                                data-satuan-besar="{{ $b->satuan_besar }}"
                                @selected(old('id_barang') == $b->id_barang)>
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
                    <label class="form-label">Jumlah Pesan & Satuan</label>
                    <div class="input-group">
                        <input type="number" name="jumlah_pesan_input" value="{{ old('jumlah_pesan_input', 1) }}"
                            class="form-control @error('jumlah_pesan_input') is-invalid @enderror" min="1" required>
                        <select name="satuan_pesan_input" id="satuanSelect" class="form-select @error('satuan_pesan_input') is-invalid @enderror" style="max-width: 150px;" required>
                            <option value="">— Satuan —</option>
                        </select>
                    </div>
                    @error('jumlah_pesan_input')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('satuan_pesan_input')
                        <div class="text-danger small mt-1">{{ $message }}</div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectBarang = document.querySelector('select[name="id_barang"]');
        const satuanSelect = document.getElementById('satuanSelect');
        const oldSatuan = "{{ old('satuan_pesan_input') }}";

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
