<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\MengirimDataTablesJson;
use App\Models\Pemasok;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemasokController extends Controller
{
    use MengirimDataTablesJson;

    public function index(): View
    {
        return view('pemasok.index');
    }

    public function data(Request $request): JsonResponse
    {
        $queryDasar = Pemasok::query();

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
            3 => 'rata_lead_time',
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
                    'rata_lead_time' => $pemasok->rata_lead_time,
                    'aksi' => view('pemasok._aksi', ['pemasok' => $pemasok])->render(),
                ];
            }
        );
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
            'rata_lead_time' => ['nullable', 'integer', 'min:1'],
        ], [], [
            'nama_pemasok' => 'nama pemasok',
            'rata_lead_time' => 'rata lead time',
        ]);

        Pemasok::query()->create([
            'nama_pemasok' => $data['nama_pemasok'],
            'alamat' => $data['alamat'] ?? null,
            'telepon' => $data['telepon'] ?? null,
            'rata_lead_time' => $data['rata_lead_time'] ?? 1,
        ]);

        return redirect()->route('pemasok.index')->with('sukses', 'Pemasok berhasil ditambahkan.');
    }

    public function show(Pemasok $pemasok): View
    {
        $pemasok->load(['daftarBarang' => fn ($q) => $q->orderBy('nama_barang')]);

        return view('pemasok.show', compact('pemasok'));
    }

    public function edit(Pemasok $pemasok): View
    {
        return view('pemasok.edit', compact('pemasok'));
    }

    public function update(Request $request, Pemasok $pemasok): RedirectResponse
    {
        $data = $request->validate([
            'nama_pemasok' => ['required', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'rata_lead_time' => ['nullable', 'integer', 'min:1'],
        ], [], [
            'nama_pemasok' => 'nama pemasok',
        ]);

        $pemasok->update([
            'nama_pemasok' => $data['nama_pemasok'],
            'alamat' => $data['alamat'] ?? null,
            'telepon' => $data['telepon'] ?? null,
            'rata_lead_time' => $data['rata_lead_time'] ?? 1,
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
