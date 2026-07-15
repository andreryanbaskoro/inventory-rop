<div class="btn-group flex-wrap">
    <a href="{{ route('transaksi.show', $transaksi) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
    @if (!\Illuminate\Support\Str::startsWith((string) $transaksi->keterangan, '[OTOMATIS-PENGADAAN:'))
        <a href="{{ route('transaksi.edit', $transaksi) }}" class="btn btn-sm btn-primary">Edit</a>
        <form action="{{ route('transaksi.destroy', $transaksi) }}" method="post" class="d-inline form-hapus"
            data-nama="{{ $transaksi->id_transaksi }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
        </form>
    @endif
</div>
