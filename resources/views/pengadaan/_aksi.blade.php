<div class="d-flex flex-nowrap gap-1 justify-content-center align-items-center">
    <form action="{{ route('pengadaan.status', $pengadaan) }}" method="post" class="d-inline">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status_pengadaan" value="Dipesan">
        <button type="submit" class="btn btn-sm {{ $pengadaan->status_pengadaan === 'Dipesan' ? 'btn-secondary' : 'btn-outline-secondary' }}" title="Set Dipesan" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Dipesan</button>
    </form>
    <form action="{{ route('pengadaan.status', $pengadaan) }}" method="post" class="d-inline">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status_pengadaan" value="Dikirim">
        <button type="submit" class="btn btn-sm {{ $pengadaan->status_pengadaan === 'Dikirim' ? 'btn-warning' : 'btn-outline-warning' }}" title="Set Dikirim" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Dikirim</button>
    </form>
    <form action="{{ route('pengadaan.status', $pengadaan) }}" method="post" class="d-inline">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status_pengadaan" value="Selesai">
        <button type="submit" class="btn btn-sm {{ $pengadaan->status_pengadaan === 'Selesai' ? 'btn-success' : 'btn-outline-success' }}" title="Set Selesai" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Selesai</button>
    </form>

    <div class="vr mx-1"></div>

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
