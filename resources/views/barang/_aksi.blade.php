<div class="d-flex flex-nowrap gap-1 justify-content-center">
    @if(isset($statusFilter) && $statusFilter === 'arsip')
        <form action="{{ route('barang.restore', $barang->id_barang) }}" method="post" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-success" title="Pulihkan (Restore)">
                <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
            </button>
        </form>
        <form action="{{ route('barang.forceDelete', $barang->id_barang) }}" method="post" class="d-inline form-musnahkan"
            data-nama="{{ $barang->nama_barang }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" title="Musnahkan Permanen" onclick="return confirm('PERINGATAN: Memusnahkan permanen barang ini juga akan MENGHAPUS SELURUH RIWAYAT TRANSAKSINYA selamanya. Lanjutkan?')">
                <i class="bi bi-trash3-fill"></i> Musnahkan
            </button>
        </form>
    @else
        <a href="{{ route('barang.show', $barang) }}" class="btn btn-sm btn-outline-info" title="Detail">
            <i class="bi bi-eye"></i>
        </a>
        <a href="{{ route('analisis.show', $barang) }}" class="btn btn-sm btn-outline-success" title="Analisis ROP">
            <i class="bi bi-graph-up"></i>
        </a>
        <a href="{{ route('transaksi.create', ['jenis' => 'Masuk', 'id_barang' => $barang->id_barang]) }}" class="btn btn-sm {{ isset($perlu_reorder) && $perlu_reorder ? 'btn-danger' : 'btn-outline-warning' }}" title="Order Barang">
            <i class="bi bi-cart-plus"></i>
        </a>
        <a href="{{ route('barang.edit', $barang) }}" class="btn btn-sm btn-outline-primary" title="Edit">
            <i class="bi bi-pencil"></i>
        </a>
        <form action="{{ route('barang.destroy', $barang) }}" method="post" class="d-inline form-hapus"
            data-nama="{{ $barang->nama_barang }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus / Pindahkan ke Tong Sampah">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    @endif
</div>
