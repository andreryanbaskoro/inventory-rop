<div class="btn-group flex-wrap">
    <a href="{{ route('pemasok.show', $pemasok) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
    <a href="{{ route('pemasok.edit', $pemasok) }}" class="btn btn-sm btn-primary">Edit</a>
    <form action="{{ route('pemasok.destroy', $pemasok) }}" method="post" class="d-inline form-hapus"
        data-nama="{{ $pemasok->nama_pemasok }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
    </form>
</div>
