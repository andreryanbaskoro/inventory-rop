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
                        <th>Lead time (hari)</th>
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
                    data: 'rata_lead_time'
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
