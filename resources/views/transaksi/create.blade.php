@extends('layouts.app')

@section('judul_halaman', 'Input Transaksi (Banyak Sekaligus)')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('transaksi.store') }}" method="post" id="formTransaksi">
                @csrf
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-dark">Jenis Transaksi</label>
                        <select name="jenis" id="selectJenis" class="form-select border-2 @error('jenis') is-invalid @enderror" required>
                            <option value="Masuk" @selected(old('jenis', $jenisAwal) === 'Masuk')>Masuk (Restock)</option>
                            <option value="Keluar" @selected(old('jenis', $jenisAwal) === 'Keluar')>Keluar (Terjual/Pakai)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-dark">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}"
                            class="form-control border-2 @error('tanggal') is-invalid @enderror" required>
                    </div>
                    <div class="col-md-6" id="containerPemasok">
                        <label class="form-label fw-bold text-dark">Pilih Pemasok <span class="text-muted fw-normal small">(Filter Barang)</span></label>
                        <select id="selectPemasok" class="form-select border-2">
                            <option value="">— Pilih Pemasok —</option>
                            @foreach ($daftarPemasok as $p)
                                <option value="{{ $p->id_pemasok }}">{{ $p->nama_pemasok }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold text-dark">Keterangan / Catatan</label>
                        <input type="text" name="keterangan" class="form-control border-2" value="{{ old('keterangan') }}" placeholder="Contoh: Restock bulan ini dari faktur INV-001">
                    </div>
                </div>

                <!-- Ceklis Barang -->
                <div class="card bg-light-secondary border-0 mb-4">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-ui-checks-grid text-primary me-2"></i>Daftar Barang</h6>
                        <div class="d-flex gap-2">
                            <button type="button" id="btnCheckAll" class="btn btn-sm btn-outline-primary rounded-pill px-3">Ceklis Semua</button>
                            <button type="button" id="btnUncheckAll" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Hapus Ceklis</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="containerBarangList" class="list-group list-group-flush" style="max-height: 500px; overflow-y: auto;">
                            <!-- Items will be injected here via JS -->
                        </div>
                        <div id="emptyState" class="text-center py-5 text-muted" style="display:none;">
                            <i class="bi bi-box-seam fs-1 mb-2 d-block opacity-50"></i>
                            <p class="mb-0" id="emptyStateMessage">Silakan pilih Pemasok terlebih dahulu untuk melihat daftar barang.</p>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-lg px-4" type="submit"><i class="bi bi-save me-2"></i>Simpan Transaksi</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-light btn-lg px-4 border">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectJenis = document.getElementById('selectJenis');
        const selectPemasok = document.getElementById('selectPemasok');
        const containerPemasok = document.getElementById('containerPemasok');
        const containerBarangList = document.getElementById('containerBarangList');
        const emptyState = document.getElementById('emptyState');
        const btnCheckAll = document.getElementById('btnCheckAll');
        const btnUncheckAll = document.getElementById('btnUncheckAll');
        
        // Data passed from controller
        const allBarang = @json($daftarBarang);
        const dataPemasok = @json($daftarPemasok);

        function renderBarangList(items, customEmptyMessage = '') {
            containerBarangList.innerHTML = '';
            
            if (items.length === 0) {
                const msg = customEmptyMessage || 'Silakan pilih Pemasok terlebih dahulu untuk melihat daftar barang.';
                document.getElementById('emptyStateMessage').textContent = msg;
                emptyState.style.display = 'block';
                return;
            }
            emptyState.style.display = 'none';

            items.forEach((item, index) => {
                const itemHtml = `
                    <div class="list-group-item list-group-item-action p-3" id="row-barang-${item.id_barang}">
                        <div class="row align-items-center">
                            <div class="col-md-5 d-flex align-items-center gap-3">
                                <div class="form-check form-switch form-switch-lg mb-0" style="padding-left: 3rem;">
                                    <input class="form-check-input item-checkbox" type="checkbox" role="switch" style="width: 2.5rem; height: 1.25rem; margin-left: -3rem; cursor:pointer;" id="check_${item.id_barang}" data-id="${item.id_barang}">
                                    <label class="form-check-label fw-bold text-dark cursor-pointer ms-2" for="check_${item.id_barang}" style="cursor:pointer;">${item.nama_barang}</label>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="d-flex gap-2 align-items-center input-area" id="input_area_${item.id_barang}" style="opacity: 0.3; pointer-events: none; transition: 0.3s all;">
                                    <!-- Hidden field for ID so it only submits when checked -->
                                    <input type="hidden" name="items[${index}][id_barang]" class="hidden-id-input" value="" disabled>
                                    
                                    <span class="text-muted small w-25 text-end">Stok saat ini: <strong>${item.stok_saat_ini}</strong></span>
                                    
                                    <input type="number" name="items[${index}][jumlah_input]" class="form-control form-control-sm qty-input border-2 fw-bold text-center" style="width: 100px;" placeholder="Jumlah" min="1" disabled>
                                    
                                    <select name="items[${index}][satuan_input]" class="form-select form-select-sm satuan-input border-2" style="width: 120px;" disabled>
                                        <option value="${item.satuan}">${item.satuan}</option>
                                        ${item.satuan_besar ? `<option value="${item.satuan_besar}">${item.satuan_besar}</option>` : ''}
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                containerBarangList.insertAdjacentHTML('beforeend', itemHtml);
            });

            // Attach event listeners to new checkboxes
            document.querySelectorAll('.item-checkbox').forEach(box => {
                box.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const row = document.getElementById(`row-barang-${id}`);
                    const inputArea = document.getElementById(`input_area_${id}`);
                    const hiddenId = inputArea.querySelector('.hidden-id-input');
                    const qtyInput = inputArea.querySelector('.qty-input');
                    const satuanInput = inputArea.querySelector('.satuan-input');

                    if (this.checked) {
                        row.classList.add('bg-primary-subtle');
                        inputArea.style.opacity = '1';
                        inputArea.style.pointerEvents = 'auto';
                        
                        hiddenId.value = id;
                        hiddenId.disabled = false;
                        
                        qtyInput.disabled = false;
                        qtyInput.required = true;
                        if (!qtyInput.value) qtyInput.value = 1;
                        
                        satuanInput.disabled = false;
                    } else {
                        row.classList.remove('bg-primary-subtle');
                        inputArea.style.opacity = '0.3';
                        inputArea.style.pointerEvents = 'none';
                        
                        hiddenId.disabled = true;
                        qtyInput.disabled = true;
                        qtyInput.required = false;
                        satuanInput.disabled = true;
                    }
                });
            });
        }

        function updateViewLogic() {
            const jenis = selectJenis.value;
            if (jenis === 'Masuk') {
                containerPemasok.style.display = 'block';
                // Trigger pemasok change logic
                const pId = selectPemasok.value;
                if (!pId) {
                    renderBarangList([], 'Silakan pilih Pemasok terlebih dahulu untuk melihat daftar barang.');
                } else {
                    const selectedPemasok = dataPemasok.find(p => p.id_pemasok == pId);
                    const barangDariPemasok = selectedPemasok ? (selectedPemasok.daftar_barang || selectedPemasok.daftarBarang || []) : [];
                    renderBarangList(barangDariPemasok, 'Wah, Pemasok ini belum memiliki barang apapun di sistem!');
                }
            } else {
                containerPemasok.style.display = 'none';
                // For 'Keluar', show all items
                renderBarangList(allBarang, 'Tidak ada satupun barang yang aktif di sistem.');
            }
        }

        selectJenis.addEventListener('change', updateViewLogic);
        selectPemasok.addEventListener('change', updateViewLogic);

        btnCheckAll.addEventListener('click', () => {
            document.querySelectorAll('.item-checkbox:not(:checked)').forEach(cb => {
                cb.checked = true;
                cb.dispatchEvent(new Event('change'));
            });
        });

        btnUncheckAll.addEventListener('click', () => {
            document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
                cb.checked = false;
                cb.dispatchEvent(new Event('change'));
            });
        });

        // Initial setup
        updateViewLogic();
        
        // Form submit validation
        document.getElementById('formTransaksi').addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Silakan ceklis minimal 1 barang untuk disimpan!');
            }
        });
    });
</script>
@endpush
