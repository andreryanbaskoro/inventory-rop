@extends('layouts.app')

@section('judul_halaman', 'Transaksi Barang')
@section('subjudul', 'Barang masuk dan keluar')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Transaksi</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="d-flex gap-2 mb-3">
        <a href="{{ route('transaksi.index') }}"
            class="btn btn-sm {{ $filterJenis ? 'btn-outline-primary' : 'btn-primary' }}">Semua</a>
        <a href="{{ route('transaksi.index', ['jenis' => 'Masuk']) }}"
            class="btn btn-sm {{ $filterJenis === 'Masuk' ? 'btn-primary' : 'btn-outline-primary' }}">Masuk</a>
        <a href="{{ route('transaksi.index', ['jenis' => 'Keluar']) }}"
            class="btn btn-sm {{ $filterJenis === 'Keluar' ? 'btn-primary' : 'btn-outline-primary' }}">Keluar</a>
    </div>
    <div class="card">
        <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <span>Daftar Transaksi</span>
            <div class="d-flex gap-2">
                <a href="{{ route('transaksi.create', ['jenis' => 'Keluar']) }}" class="btn btn-warning btn-sm"><i class="bi bi-plus"></i> Keluar</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="tabelTransaksi" style="width:100%">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>ID</th>
                        <th>Barang</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
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
        const filterJenis = @json($filterJenis);
        $.fn.dataTable.ext.errMode = 'none';
        new DataTable('#tabelTransaksi', {
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('transaksi.data') }}',
                data: function(d) {
                    d.filter_jenis = filterJenis;
                }
            },
            columns: [{
                    data: 'tanggal'
                },
                {
                    data: 'id_transaksi'
                },
                {
                    data: 'barang'
                },
                {
                    data: 'jenis'
                },
                {
                    data: 'jumlah'
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
