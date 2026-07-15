<div class="d-flex flex-nowrap gap-1 justify-content-center">
    <a href="{{ route('transaksi.show', $transaksi) }}" class="btn btn-sm btn-outline-info" title="Detail">
        <i class="bi bi-eye"></i>
    </a>
    @if (!\Illuminate\Support\Str::startsWith((string) $transaksi->keterangan, '[OTOMATIS-PENGADAAN:'))
        <a href="{{ route('transaksi.edit', $transaksi) }}" class="btn btn-sm btn-outline-primary" title="Edit">
            <i class="bi bi-pencil"></i>
        </a>
        <form action="{{ route('transaksi.destroy', $transaksi) }}" method="post" class="d-inline form-hapus"
            data-nama="{{ $transaksi->id_transaksi }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    @endif
</div>
