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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peringatanReorder as $item)
                            <tr>
                                <td>{{ $item['barang']->nama_barang }}</td>
                                <td>{{ $item['barang']->pemasok?->nama_pemasok ?? '-' }}</td>
                                <td><span class="badge bg-danger">{{ $item['barang']->stok_saat_ini }} {{ $item['barang']->satuan }}</span></td>
                                <td><strong>{{ ceil($item['rop']) }} {{ $item['barang']->satuan }}</strong></td>
                                <td>
                                    <a href="{{ route('transaksi.create', ['jenis' => 'Masuk', 'id_barang' => $item['barang']->id_barang]) }}" class="btn btn-sm btn-primary py-0"><i class="bx bx-cart-add"></i> Order</a>
                                    <a href="{{ route('analisis.show', $item['barang']->id_barang) }}" class="btn btn-sm btn-info py-0 text-white"><i class="bx bx-line-chart"></i> Analisis</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <ul class="nav nav-pills">
            <li class="nav-item me-2">
                <a class="nav-link {{ ($statusFilter ?? 'aktif') === 'aktif' ? 'active bg-primary shadow-sm' : 'bg-white text-dark border' }}" href="{{ route('barang.index', ['status' => 'aktif']) }}">
                    <i class="bi bi-box-seam me-1"></i> Daftar Barang <span class="badge {{ ($statusFilter ?? 'aktif') === 'aktif' ? 'bg-white text-primary' : 'bg-secondary text-white' }} ms-1">{{ $jumlahAktif ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($statusFilter ?? 'aktif') === 'arsip' ? 'active bg-danger text-white fw-bold shadow-sm' : 'bg-white text-danger border' }}" style="{{ ($statusFilter ?? 'aktif') === 'arsip' ? 'color: #ffffff !important;' : '' }}" href="{{ route('barang.index', ['status' => 'arsip']) }}">
                    <i class="bi bi-trash3 me-1"></i> Tong Sampah (Recycle Bin) <span class="badge {{ ($statusFilter ?? 'aktif') === 'arsip' ? 'bg-white text-danger fw-bold' : 'bg-danger text-white' }} ms-1">{{ $jumlahArsip ?? 0 }}</span>
                </a>
            </li>
        </ul>
        @if(($statusFilter ?? 'aktif') === 'aktif')
            <a href="{{ route('barang.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Barang
            </a>
        @endif
    </div>

    @if(($statusFilter ?? 'aktif') === 'arsip')
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-3">
            <i class="bi bi-info-circle-fill fs-4 me-3 text-info"></i>
            <div>
                <strong>Informasi Arsip Tong Sampah:</strong> Barang di halaman ini adalah barang yang telah dihapus sementara. 
                Selama barang berada di sini, <strong>seluruh riwayat transaksinya juga disembunyikan otomatis</strong> dari menu Transaksi dan Laporan. Klik <strong>Pulihkan</strong> jika ingin mengembalikan barang & riwayat transaksinya seperti semula.
            </div>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header {{ ($statusFilter ?? 'aktif') === 'arsip' ? 'bg-danger' : 'bg-white' }} py-3" style="{{ ($statusFilter ?? 'aktif') === 'arsip' ? 'background-color: #dc3545 !important; color: #ffffff !important;' : '' }}">
            <h6 class="m-0 font-weight-bold {{ ($statusFilter ?? 'aktif') === 'arsip' ? 'text-white' : '' }}" style="{{ ($statusFilter ?? 'aktif') === 'arsip' ? 'color: #ffffff !important;' : '' }}">{{ ($statusFilter ?? 'aktif') === 'arsip' ? '📦 Daftar Barang Terhapus Sementara (Tong Sampah)' : '📦 Daftar Master Data Barang' }}</h6>
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
            ajax: '{!! route("barang.data", ["status" => $statusFilter ?? "aktif"]) !!}',
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
