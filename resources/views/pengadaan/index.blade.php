@extends('layouts.app')

@section('judul_halaman', 'Pengadaan Barang')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Pengadaan</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Pengadaan</span>
            <a href="{{ route('pengadaan.create') }}" class="btn btn-primary btn-sm">Tambah</a>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="tabelPengadaan" style="width:100%">
                <thead>
                    <tr>
                        <th>Tanggal pesan</th>
                        <th>ID</th>
                        <th>Barang</th>
                        <th>Pemasok</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
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
        new DataTable('#tabelPengadaan', {
            processing: true,
            serverSide: true,
            ajax: '{{ route('pengadaan.data') }}',
            columns: [{
                    data: 'tanggal_pesan'
                },
                {
                    data: 'id_pengadaan'
                },
                {
                    data: 'barang'
                },
                {
                    data: 'pemasok'
                },
                {
                    data: 'jumlah_pesan'
                },
                {
                    data: 'status_pengadaan'
                },
                {
                    data: 'aksi',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [0, 'desc']
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/2.0.8/i18n/id.json'
            }
        });
    </script>
@endpush
