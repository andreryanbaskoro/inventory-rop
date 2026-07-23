<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\MengirimDataTablesJson;
use App\Models\Pemasok;
use App\Services\AnalisisRopEoqService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemasokController extends Controller
{
    use MengirimDataTablesJson;

    private AnalisisRopEoqService $analisisService;

    public function __construct(AnalisisRopEoqService $analisisService)
    {
        $this->analisisService = $analisisService;
    }

    public function index(): View
    {
        return view('pemasok.index');
    }

    public function data(Request $request): JsonResponse
    {
        $queryDasar = Pemasok::withCount('daftarBarang');

        $query = clone $queryDasar;

        $pencarian = $request->input('search.value');
        if (is_string($pencarian) && $pencarian !== '') {
            $like = '%'.$pencarian.'%';
            $query->where(function ($q) use ($like) {
                $q->where('nama_pemasok', 'like', $like)
                    ->orWhere('id_pemasok', 'like', $like)
                    ->orWhere('telepon', 'like', $like);
            });
        }

        $urutanKolom = [
            0 => 'id_pemasok',
            1 => 'nama_pemasok',
            2 => 'telepon',
        ];

        return $this->responseDataTables(
            $request,
            $query,
            $queryDasar,
            $urutanKolom,
            function (Pemasok $pemasok) {
                return [
                    'id_pemasok' => $pemasok->id_pemasok,
                    'nama_pemasok' => $pemasok->nama_pemasok,
                    'telepon' => $pemasok->telepon ?? '-',
                    'jumlah_barang' => $pemasok->daftar_barang_count,
                    'aksi' => view('pemasok._aksi', ['pemasok' => $pemasok])->render(),
                ];
            }
        );
    }

    public function barang(Pemasok $pemasok): JsonResponse
    {
        $barang = $pemasok->daftarBarang()
            ->orderBy('nama_barang')
            ->get()
            ->map(function ($b) {
                $lt = $b->lead_time_hari . ' Hari';
                if ($b->lead_time_menit > 0) {
                    $lt .= ' ' . $b->lead_time_menit . ' Menit';
                }
                $analisis = $this->analisisService->hitungUntukBarang($b);
                return [
                    'id_barang' => $b->id_barang,
                    'nama_barang' => $b->nama_barang,
                    'stok_saat_ini' => $b->stok_saat_ini,
                    'satuan' => $b->satuan,
                    'lead_time' => $lt,
                    'safety_stock' => round($analisis['safety_stock'], 2),
                    'rop' => round($analisis['rop'], 2),
                    'perlu_reorder' => $analisis['perlu_reorder'],
                    'status_barang' => $b->status_barang,
                    'url_masuk' => route('transaksi.create', ['jenis' => 'Masuk', 'id_barang' => $b->id_barang]),
                ];
            });

        return response()->json(['data' => $barang]);
    }

    public function create(): View
    {
        return view('pemasok.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_pemasok' => ['required', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
        ], [], [
            'nama_pemasok' => 'nama pemasok',
        ]);

        Pemasok::query()->create([
            'nama_pemasok' => $data['nama_pemasok'],
            'alamat' => $data['alamat'] ?? null,
            'telepon' => $data['telepon'] ?? null,
        ]);

        return redirect()->route('pemasok.index')->with('sukses', 'Pemasok berhasil ditambahkan.');
    }

    public function show(Pemasok $pemasok): View
    {
        $pemasok->load(['daftarBarang' => fn ($q) => $q->orderBy('nama_barang')]);

        $analisisBarang = [];
        foreach ($pemasok->daftarBarang as $b) {
            $analisisBarang[$b->id_barang] = $this->analisisService->hitungUntukBarang($b);
        }

        return view('pemasok.show', compact('pemasok', 'analisisBarang'));
    }

    public function edit(Pemasok $pemasok): View
    {
        $pemasok->load(['daftarBarang' => fn ($q) => $q->orderBy('nama_barang')]);

        $analisisBarang = [];
        foreach ($pemasok->daftarBarang as $b) {
            $analisisBarang[$b->id_barang] = $this->analisisService->hitungUntukBarang($b);
        }

        return view('pemasok.edit', compact('pemasok', 'analisisBarang'));
    }

    public function update(Request $request, Pemasok $pemasok): RedirectResponse
    {
        $data = $request->validate([
            'nama_pemasok' => ['required', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
        ], [], [
            'nama_pemasok' => 'nama pemasok',
        ]);

        $pemasok->update([
            'nama_pemasok' => $data['nama_pemasok'],
            'alamat' => $data['alamat'] ?? null,
            'telepon' => $data['telepon'] ?? null,
        ]);

        return redirect()->route('pemasok.index')->with('sukses', 'Pemasok berhasil diperbarui.');
    }

    public function destroy(Pemasok $pemasok): RedirectResponse
    {
        try {
            $pemasok->delete();
        } catch (\Throwable $e) {
            return redirect()->route('pemasok.index')->with('error', 'Pemasok tidak dapat dihapus karena masih digunakan oleh barang.');
        }

        return redirect()->route('pemasok.index')->with('sukses', 'Pemasok berhasil dihapus.');
    }
}
