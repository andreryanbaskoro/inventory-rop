<div class="d-flex flex-nowrap gap-1 justify-content-center">
    <a href="{{ route('barang.show', $barang) }}" class="btn btn-sm btn-outline-info" title="Detail">
        <i class="bi bi-eye"></i>
    </a>
    <a href="{{ route('barang.edit', $barang) }}" class="btn btn-sm btn-outline-primary" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>
    <form action="{{ route('barang.destroy', $barang) }}" method="post" class="d-inline form-hapus"
        data-nama="{{ $barang->nama_barang }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>
