<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\MengirimDataTablesJson;
use App\Models\Barang;
use App\Models\Pemasok;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarangController extends Controller
{
    use MengirimDataTablesJson;

    public function index(): View
    {
        return view('barang.index');
    }

    public function data(Request $request): JsonResponse
    {
        $queryDasar = Barang::query()->with('pemasok');

        $query = clone $queryDasar;

        $pencarian = $request->input('search.value');
        if (is_string($pencarian) && $pencarian !== '') {
            $like = '%'.$pencarian.'%';
            $query->where(function ($q) use ($like) {
                $q->where('nama_barang', 'like', $like)
                    ->orWhere('id_barang', 'like', $like)
                    ->orWhereHas('pemasok', function ($p) use ($like) {
                        $p->where('nama_pemasok', 'like', $like);
                    });
            });
        }

        $urutanKolom = [
            0 => 'id_barang',
            1 => 'nama_barang',
            2 => 'stok_saat_ini',
            3 => 'stok_minimum',
            4 => 'harga_jual',
            5 => 'status_barang',
        ];

        return $this->responseDataTables(
            $request,
            $query,
            $queryDasar,
            $urutanKolom,
            function (Barang $barang) {
                return [
                    'id_barang' => $barang->id_barang,
                    'nama_barang' => $barang->nama_barang,
                    'pemasok' => $barang->pemasok?->nama_pemasok,
                    'stok_saat_ini' => $barang->stok_saat_ini . ' ' . $barang->satuan,
                    'stok_minimum' => $barang->stok_minimum,
                    'harga_jual' => number_format((float) $barang->harga_jual, 0, ',', '.'),
                    'status_barang' => $barang->status_barang,
                    'aksi' => view('barang._aksi', ['barang' => $barang])->render(),
                ];
            }
        );
    }

    public function create(): View
    {
        $daftarPemasok = Pemasok::query()->orderBy('nama_pemasok')->get();

        return view('barang.create', compact('daftarPemasok'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_pemasok' => ['required', 'exists:pemasok,id_pemasok'],
            'nama_barang' => ['required', 'string', 'max:150'],
            'satuan' => ['nullable', 'string', 'max:30'],
            'satuan_besar' => ['nullable', 'string', 'max:30'],
            'isi_per_satuan_besar' => ['nullable', 'integer', 'min:1'],
            'stok_saat_ini' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['nullable', 'integer', 'min:0'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'biaya_pesan' => ['nullable', 'numeric', 'min:0'],
            'biaya_simpan' => ['nullable', 'numeric', 'min:0'],
            'status_barang' => ['required', 'in:Aktif,Nonaktif'],
        ], [], [
            'id_pemasok' => 'pemasok',
            'nama_barang' => 'nama barang',
        ]);

        Barang::query()->create([
            'id_pemasok' => $data['id_pemasok'],
            'nama_barang' => $data['nama_barang'],
            'satuan' => $data['satuan'] ?? 'PCS',
            'satuan_besar' => $data['satuan_besar'] ?? null,
            'isi_per_satuan_besar' => $data['isi_per_satuan_besar'] ?? null,
            'stok_saat_ini' => $data['stok_saat_ini'],
            'stok_minimum' => $data['stok_minimum'] ?? 0,
            'harga_beli' => $data['harga_beli'],
            'harga_jual' => $data['harga_jual'],
            'biaya_pesan' => $data['biaya_pesan'] ?? 0,
            'biaya_simpan' => $data['biaya_simpan'] ?? 0,
            'status_barang' => $data['status_barang'],
        ]);

        return redirect()->route('barang.index')->with('sukses', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang): View
    {
        $barang->load(['pemasok', 'daftarTransaksi' => fn ($q) => $q->latest('tanggal')->limit(20)]);

        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang): View
    {
        $daftarPemasok = Pemasok::query()->orderBy('nama_pemasok')->get();

        return view('barang.edit', compact('barang', 'daftarPemasok'));
    }

    public function update(Request $request, Barang $barang): RedirectResponse
    {
        $data = $request->validate([
            'id_pemasok' => ['required', 'exists:pemasok,id_pemasok'],
            'nama_barang' => ['required', 'string', 'max:150'],
            'satuan' => ['nullable', 'string', 'max:30'],
            'satuan_besar' => ['nullable', 'string', 'max:30'],
            'isi_per_satuan_besar' => ['nullable', 'integer', 'min:1'],
            'stok_saat_ini' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['nullable', 'integer', 'min:0'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'biaya_pesan' => ['nullable', 'numeric', 'min:0'],
            'biaya_simpan' => ['nullable', 'numeric', 'min:0'],
            'status_barang' => ['required', 'in:Aktif,Nonaktif'],
        ], [], [
            'id_pemasok' => 'pemasok',
        ]);

        $barang->update([
            'id_pemasok' => $data['id_pemasok'],
            'nama_barang' => $data['nama_barang'],
            'satuan' => $data['satuan'] ?? 'PCS',
            'satuan_besar' => $data['satuan_besar'] ?? null,
            'isi_per_satuan_besar' => $data['isi_per_satuan_besar'] ?? null,
            'stok_saat_ini' => $data['stok_saat_ini'],
            'stok_minimum' => $data['stok_minimum'] ?? 0,
            'harga_beli' => $data['harga_beli'],
            'harga_jual' => $data['harga_jual'],
            'biaya_pesan' => $data['biaya_pesan'] ?? 0,
            'biaya_simpan' => $data['biaya_simpan'] ?? 0,
            'status_barang' => $data['status_barang'],
        ]);

        return redirect()->route('barang.index')->with('sukses', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang): RedirectResponse
    {
        try {
            $barang->delete();
        } catch (\Throwable $e) {
            return redirect()->route('barang.index')->with('error', 'Barang tidak dapat dihapus karena masih memiliki data terkait.');
        }

        return redirect()->route('barang.index')->with('sukses', 'Barang berhasil dihapus.');
    }
}
