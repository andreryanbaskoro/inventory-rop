@extends('layouts.app')

@section('judul_halaman', 'Daftar Barang')
@section('subjudul', 'Kelola data barang toko')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Barang</li>
        </ol>
    </nav>
@endsection

@section('konten')
    @if(isset($peringatanReorder) && $peringatanReorder->isNotEmpty())
        <div class="alert alert-warning alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <h5 class="alert-heading text-dark mb-2"><i class="bx bx-error-circle text-warning"></i> Perhatian: Barang Perlu Re-order!</h5>
            <p class="mb-2">Beberapa barang telah mencapai atau berada di bawah titik <strong>Reorder Point (ROP)</strong>. Segera lakukan pengadaan.</p>
            <div class="table-responsive">
                <table class="table table-sm table-bordered bg-white mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Barang</th>
                            <th>Pemasok</th>
                            <th>Stok Saat Ini</th>
                            <th>Batas ROP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peringatanReorder as $item)
                            <tr>
                                <td>{{ $item['barang']->nama_barang }}</td>
                                <td>{{ $item['barang']->pemasok?->nama_pemasok ?? '-' }}</td>
                                <td><span class="badge bg-danger">{{ $item['barang']->stok_saat_ini }} {{ $item['barang']->satuan }}</span></td>
                                <td><strong>{{ ceil($item['rop']) }} {{ $item['barang']->satuan }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Data Barang</span>
            <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">Tambah Barang</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="tabelBarang" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Pemasok</th>
                            <th>Stok</th>
                            <th>Lead Time</th>
                            <th>Safety Stock</th>
                            <th>ROP</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        $.fn.dataTable.ext.errMode = 'none';
        const tabel = new DataTable('#tabelBarang', {
            processing: true,
            serverSide: true,
            ajax: '{{ route('barang.data') }}',
            columns: [{
                    data: 'id_barang'
                },
                {
                    data: 'nama_barang'
                },
                {
                    data: 'pemasok'
                },
                {
                    data: 'stok_saat_ini'
                },
                {
                    data: 'lead_time',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'safety_stock',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'rop',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status_barang'
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
    </script>
@endpush
