@extends('layouts.app')

@section('judul_halaman', 'Edit Pemasok')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('pemasok.index') }}">Pemasok</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
@endsection

@section('konten')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('pemasok.update', $pemasok) }}" method="post" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label class="form-label">Nama pemasok</label>
                    <input type="text" name="nama_pemasok" value="{{ old('nama_pemasok', $pemasok->nama_pemasok) }}"
                        class="form-control @error('nama_pemasok') is-invalid @enderror" required>
                    @error('nama_pemasok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $pemasok->telepon) }}"
                        class="form-control">
                </div>

                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" rows="3" class="form-control">{{ old('alamat', $pemasok->alamat) }}</textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Perbarui</button>
                    <a href="{{ route('pemasok.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-box-seam me-2"></i>Barang dari pemasok ini</span>
            <div>
                @php
                    $jumlahReorder = collect($analisisBarang)->where('perlu_reorder', true)->count();
                @endphp
                @if($jumlahReorder > 0)
                    <span class="badge bg-danger rounded-pill me-1">{{ $jumlahReorder }} Perlu Reorder</span>
                @endif
                <span class="badge bg-primary rounded-pill">{{ $pemasok->daftarBarang->count() }} Barang</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($pemasok->daftarBarang->isEmpty())
                <div class="text-center py-4">
                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                    <p class="mt-2 text-muted">Belum ada barang dari pemasok ini</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
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
                        <tbody>
                            @foreach ($pemasok->daftarBarang as $b)
                                @php
                                    $a = $analisisBarang[$b->id_barang] ?? null;
                                    $reorder = $a['perlu_reorder'] ?? false;
                                @endphp
                                <tr class="{{ $reorder ? 'table-danger' : '' }}">
                                    <td><a href="{{ route('barang.edit', $b) }}">{{ $b->nama_barang }}</a></td>
                                    <td class="{{ $b->stok_saat_ini <= 0 ? 'text-danger fw-bold' : '' }}">
                                        {{ $b->stok_saat_ini }} {{ $b->satuan }}
                                    </td>
                                    <td>{{ $b->lead_time_hari }} Hari{{ $b->lead_time_menit > 0 ? ' ' . $b->lead_time_menit . ' Mnt' : '' }}</td>
                                    <td>{{ $a ? number_format($a['rop'], 2) : '-' }}</td>
                                    <td>{{ $a ? number_format($a['safety_stock'], 2) : '-' }}</td>
                                    <td>
                                        @if($reorder)
                                            <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Reorder</span>
                                        @else
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aman</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($reorder)
                                            <a href="{{ route('transaksi.create', ['jenis' => 'Masuk', 'id_barang' => $b->id_barang]) }}" class="btn btn-sm btn-danger">
                                                <i class="bi bi-lightning-fill"></i> Order
                                            </a>
                                        @else
                                            <a href="{{ route('transaksi.create', ['jenis' => 'Masuk', 'id_barang' => $b->id_barang]) }}" class="btn btn-sm btn-success">
                                                <i class="bi bi-plus-lg"></i> Masuk
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
