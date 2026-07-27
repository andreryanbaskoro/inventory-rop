@extends('layouts.app')

@section('judul_halaman', 'Backup Database')
@section('subjudul', 'Amankan data inventaris dan sistem Anda')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Backup Database</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white p-4 border-bottom text-center">
                    <div class="d-inline-flex justify-content-center align-items-center rounded-circle mb-3" style="width: 80px; height: 80px; background-color: #e0f2fe; color: #0284c7;">
                        <i class="bi bi-hdd-stack-fill" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Export Database</h5>
                    <p class="text-muted mb-0 small">Unduh seluruh data sistem dalam format SQL</p>
                </div>
                
                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>{{ session('error') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="alert alert-info border-0 bg-light-info d-flex" role="alert">
                        <i class="bi bi-info-circle-fill text-info fs-4 me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Informasi Backup:</h6>
                            <ul class="mb-0 ps-3 small text-muted">
                                <li>File akan diunduh dengan ekstensi <strong>.sql</strong>.</li>
                                <li>Berisi seluruh tabel: Barang, Pemasok, Transaksi, Pengguna, dll.</li>
                                <li>Simpan file ini di tempat yang aman (Flashdisk, Google Drive, dll).</li>
                                <li>Proses backup tidak akan membebani server dan langsung selesai dalam hitungan detik.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('backup.download') }}" class="btn btn-primary btn-lg fw-bold d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-cloud-download-fill fs-5"></i>
                            Mulai Unduh Backup (.sql)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
