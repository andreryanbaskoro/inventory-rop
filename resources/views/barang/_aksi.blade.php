<div class="btn-group flex-wrap" role="group">
    <a href="{{ route('barang.show', $barang) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
    <a href="{{ route('barang.edit', $barang) }}" class="btn btn-sm btn-primary">Edit</a>
    <form action="{{ route('barang.destroy', $barang) }}" method="post" class="d-inline form-hapus"
        data-nama="{{ $barang->nama_barang }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
    </form>
</div>
