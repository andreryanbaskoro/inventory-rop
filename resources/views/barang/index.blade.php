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
                            <th>Min</th>
                            <th>Harga Jual</th>
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
                    data: 'stok_minimum'
                },
                {
                    data: 'harga_jual'
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
