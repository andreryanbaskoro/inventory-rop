@extends('layouts.app')

@section('judul_halaman', 'Pemasok')
@section('subjudul', 'Data pemasok barang')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Pemasok</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Pemasok</span>
            <a href="{{ route('pemasok.create') }}" class="btn btn-primary btn-sm">Tambah</a>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="tabelPemasok" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Barang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

<div class="modal fade" id="modalBarang" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-box-seam me-2"></i>Barang dari <span id="namaPemasokModal"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tabelBarangModal">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Lead Time</th>
                                <th>ROP</th>
                                <th>Safety Stock</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div id="loadingBarang" class="text-center py-4 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Memuat data barang...</p>
                </div>
                <div id="emptyBarang" class="text-center py-4 d-none">
                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                    <p class="mt-2 text-muted">Belum ada barang dari pemasok ini</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $.fn.dataTable.ext.errMode = 'none';
        new DataTable('#tabelPemasok', {
            processing: true,
            serverSide: true,
            ajax: '{{ route('pemasok.data') }}',
            columns: [{
                    data: 'id_pemasok'
                },
                {
                    data: 'nama_pemasok'
                },
                {
                    data: 'telepon'
                },
                {
                    data: 'jumlah_barang',
                    render: function(data, type, row) {
                        return `<button class="btn btn-sm btn-outline-primary rounded-pill btn-lihat-barang" data-id="${row.id_pemasok}" data-nama="${row.nama_pemasok}"><i class="bi bi-box-seam"></i> ${data} Barang</button>`;
                    }
                },
                {
                    data: 'aksi',
                    orderable: false,
                    searchable: false
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/2.0.8/i18n/id.json'
            }
        });

        // Modal Barang Handler
        const modalBarang = new bootstrap.Modal(document.getElementById('modalBarang'));
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-lihat-barang');
            if (!btn) return;
            
            const idPemasok = btn.dataset.id;
            const namaPemasok = btn.dataset.nama;
            
            document.getElementById('namaPemasokModal').textContent = namaPemasok;
            document.getElementById('loadingBarang').classList.remove('d-none');
            document.getElementById('emptyBarang').classList.add('d-none');
            document.getElementById('tabelBarangModal').querySelector('tbody').innerHTML = '';
            
            modalBarang.show();
            
            fetch('/pemasok/' + idPemasok + '/barang')
                .then(r => r.json())
                .then(result => {
                    document.getElementById('loadingBarang').classList.add('d-none');
                    const tbody = document.getElementById('tabelBarangModal').querySelector('tbody');
                    
                    if (result.data.length === 0) {
                        document.getElementById('emptyBarang').classList.remove('d-none');
                        return;
                    }
                    
                    result.data.forEach(b => {
                        const stokClass = b.stok_saat_ini <= 0 ? 'text-danger fw-bold' : '';
                        const rowClass = b.perlu_reorder ? 'table-danger' : '';
                        const statusBadge = b.perlu_reorder
                            ? '<span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Reorder</span>'
                            : '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aman</span>';
                        const aksiBtn = b.perlu_reorder
                            ? `<a href="${b.url_masuk}" class="btn btn-sm btn-danger"><i class="bi bi-lightning-fill"></i> Order</a>`
                            : `<a href="${b.url_masuk}" class="btn btn-sm btn-success"><i class="bi bi-plus-lg"></i> Masuk</a>`;
                        
                        tbody.innerHTML += `<tr class="${rowClass}">
                            <td>${b.nama_barang}</td>
                            <td class="${stokClass}">${b.stok_saat_ini} ${b.satuan}</td>
                            <td>${b.lead_time}</td>
                            <td>${b.rop}</td>
                            <td>${b.safety_stock}</td>
                            <td>${statusBadge}</td>
                            <td>${aksiBtn}</td>
                        </tr>`;
                    });
                })
                .catch(err => {
                    document.getElementById('loadingBarang').classList.add('d-none');
                    document.getElementById('tabelBarangModal').querySelector('tbody').innerHTML = '<tr><td colspan="7" class="text-center text-danger">Gagal memuat data</td></tr>';
                });
        });
    </script>
@endpush
