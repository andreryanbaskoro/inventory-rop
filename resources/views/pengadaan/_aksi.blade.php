<div class="d-flex flex-nowrap gap-1 justify-content-center">
    <a href="{{ route('pengadaan.show', $pengadaan) }}" class="btn btn-sm btn-outline-info" title="Detail">
        <i class="bi bi-eye"></i>
    </a>
    <a href="{{ route('pengadaan.edit', $pengadaan) }}" class="btn btn-sm btn-outline-primary" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>
    <form action="{{ route('pengadaan.destroy', $pengadaan) }}" method="post" class="d-inline form-hapus"
        data-nama="{{ $pengadaan->id_pengadaan }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>
