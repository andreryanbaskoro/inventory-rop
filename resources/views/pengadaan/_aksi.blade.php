<div class="btn-group flex-wrap">
    <a href="{{ route('pengadaan.show', $pengadaan) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
    <a href="{{ route('pengadaan.edit', $pengadaan) }}" class="btn btn-sm btn-primary">Edit</a>
    <form action="{{ route('pengadaan.destroy', $pengadaan) }}" method="post" class="d-inline form-hapus"
        data-nama="{{ $pengadaan->id_pengadaan }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
    </form>
</div>
