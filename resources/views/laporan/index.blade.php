@extends('layouts.app')

@section('judul_halaman', 'Laporan')
@section('subjudul', $data['judul'] . ($periode ? ' â€” periode ' . $periode : ''))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Laporan</li>
        </ol>
    </nav>
@endsection

@section('konten')
    @php
        $queryEkspor = array_filter([
            'jenis' => $jenis,
            'dari' => $dari,
            'sampai' => $sampai,
        ]);
    @endphp

    <div class="card">
        <div class="card-body">
            <form method="get" action="{{ route('laporan.index') }}" class="row g-3 align-items-end mb-4 laporan-filter">
                <div class="col-md-4">
                    <label class="form-label" for="jenis">Jenis laporan</label>
                    <select name="jenis" id="jenis" class="form-select">
                        @foreach ($daftarJenis as $kunci => $label)
                            <option value="{{ $kunci }}" @selected($jenis === $kunci)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 rentang-tanggal {{ $jenis === 'stok' ? 'd-none' : '' }}">
                    <label class="form-label" for="dari">Dari tanggal</label>
                    <input type="date" name="dari" id="dari" class="form-control" value="{{ $dari }}">
                </div>
                <div class="col-md-3 rentang-tanggal {{ $jenis === 'stok' ? 'd-none' : '' }}">
                    <label class="form-label" for="sampai">Sampai tanggal</label>
                    <input type="date" name="sampai" id="sampai" class="form-control" value="{{ $sampai }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                </div>
            </form>

            <div class="d-flex flex-wrap gap-2 mb-4 laporan-aksi">
                <a href="{{ route('laporan.excel', $queryEkspor) }}" class="btn btn-success btn-sm">Export Excel</a>
                <a href="{{ route('laporan.pdf', $queryEkspor) }}" class="btn btn-danger btn-sm">Export PDF</a>
                <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()">Cetak</button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            @foreach ($data['kolom'] as $label)
                                <th>{{ $label }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data['baris'] as $baris)
                            <tr>
                                @foreach ($baris as $sel)
                                    <td>{{ $sel }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($data['kolom']) }}" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        @media print {
            #sidebar,
            .sidebar-wrapper,
            header,
            footer,
            .breadcrumb,
            .page-title,
            .laporan-filter,
            .laporan-aksi,
            .sidebar-toggler {
                display: none !important;
            }

            #main {
                margin-left: 0 !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function() {
            const jenis = document.getElementById('jenis');
            const rentang = document.querySelectorAll('.rentang-tanggal');

            jenis.addEventListener('change', function() {
                const sembunyi = jenis.value === 'stok';
                rentang.forEach(el => el.classList.toggle('d-none', sembunyi));
            });
        })();
    </script>
@endpush
