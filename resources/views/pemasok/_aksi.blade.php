<div class="d-flex flex-nowrap gap-1 justify-content-center">
    <a href="{{ route('pemasok.show', $pemasok) }}" class="btn btn-sm btn-outline-info" title="Detail">
        <i class="bi bi-eye"></i>
    </a>
    <a href="{{ route('pemasok.edit', $pemasok) }}" class="btn btn-sm btn-outline-primary" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>
    <form action="{{ route('pemasok.destroy', $pemasok) }}" method="post" class="d-inline form-hapus"
        data-nama="{{ $pemasok->nama_pemasok }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>
