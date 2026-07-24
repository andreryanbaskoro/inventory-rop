<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\MengirimDataTablesJson;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Services\StokBarangService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    use MengirimDataTablesJson;

    public function __construct(
        protected StokBarangService $stokBarangService
    ) {}

    public function index(Request $request): View
    {
        $filterJenis = $request->query('jenis');
        if (! in_array($filterJenis, ['Masuk', 'Keluar'], true)) {
            $filterJenis = null;
        }

        return view('transaksi.index', compact('filterJenis'));
    }

    public function data(Request $request): JsonResponse
    {
        $queryTanpa = Transaksi::query()->with('barang');

        if ($request->filled('filter_jenis') && in_array($request->filter_jenis, ['Masuk', 'Keluar'], true)) {
            $queryTanpa->where('jenis', $request->filter_jenis);
        }

        $query = Transaksi::query()->with('barang');
        if ($request->filled('filter_jenis') && in_array($request->filter_jenis, ['Masuk', 'Keluar'], true)) {
            $query->where('jenis', $request->filter_jenis);
        }

        $pencarian = $request->input('search.value');
        if (is_string($pencarian) && $pencarian !== '') {
            $like = '%'.$pencarian.'%';
            $query->where(function ($q) use ($like) {
                $q->where('id_transaksi', 'like', $like)
                    ->orWhere('keterangan', 'like', $like)
                    ->orWhereHas('barang', function ($b) use ($like) {
                        $b->where('nama_barang', 'like', $like);
                    });
            });
        }

        $urutanKolom = [
            0 => 'tanggal',
            1 => 'id_transaksi',
            2 => 'jenis',
            3 => 'jumlah',
        ];

        return $this->responseDataTables(
            $request,
            $query,
            $queryTanpa,
            $urutanKolom,
            function (Transaksi $transaksi) {
                return [
                    'tanggal' => $transaksi->tanggal->format('d/m/Y'),
                    'id_transaksi' => $transaksi->id_transaksi,
                    'barang' => $transaksi->barang?->nama_barang,
                    'jenis' => $transaksi->jenis === 'Masuk'
                        ? '<span class="badge bg-success">Masuk</span>'
                        : '<span class="badge bg-warning text-dark">Keluar</span>',
                    'jumlah' => $transaksi->satuan_input != $transaksi->barang?->satuan 
                                    ? $transaksi->jumlah_input . ' ' . $transaksi->satuan_input . ' (' . $transaksi->jumlah . ' ' . $transaksi->barang?->satuan . ')'
                                    : $transaksi->jumlah . ' ' . $transaksi->barang?->satuan,
                    'aksi' => view('transaksi._aksi', ['transaksi' => $transaksi])->render(),
                ];
            }
        );
    }

    public function create(Request $request): View
    {
        $daftarBarang = Barang::query()->where('status_barang', 'Aktif')->orderBy('nama_barang')->get();
        $daftarPemasok = \App\Models\Pemasok::query()
            ->with(['barang' => function($q) {
                $q->where('status_barang', 'Aktif')->orderBy('nama_barang');
            }])
            ->orderBy('nama_pemasok')
            ->get();
            
        $jenisAwal = $request->query('jenis');
        if (! in_array($jenisAwal, ['Masuk', 'Keluar'], true)) {
            $jenisAwal = 'Masuk';
        }

        return view('transaksi.create', compact('daftarBarang', 'daftarPemasok', 'jenisAwal'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'jenis' => ['required', 'in:Masuk,Keluar'],
            'keterangan' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_barang' => ['required', 'exists:barang,id_barang'],
            'items.*.jumlah_input' => ['required', 'integer', 'min:1'],
            'items.*.satuan_input' => ['required', 'string'],
        ], [
            'items.required' => 'Silakan ceklis minimal 1 barang.',
        ]);

        try {
            DB::transaction(function () use ($data) {
                foreach ($data['items'] as $item) {
                    $barang = Barang::query()->lockForUpdate()->findOrFail($item['id_barang']);

                    // Hitung jumlah sebenarnya (base unit)
                    $jumlahReal = $item['jumlah_input'];
                    if ($barang->satuan_besar && $item['satuan_input'] === $barang->satuan_besar) {
                        $jumlahReal = $item['jumlah_input'] * $barang->isi_per_satuan_besar;
                    }

                    Transaksi::query()->create([
                        'id_barang' => $item['id_barang'],
                        'tanggal' => $data['tanggal'],
                        'jenis' => $data['jenis'],
                        'jumlah' => $jumlahReal,
                        'satuan_input' => $item['satuan_input'],
                        'jumlah_input' => $item['jumlah_input'],
                        'keterangan' => $data['keterangan'] ?? null,
                    ]);

                    if ($data['jenis'] === 'Masuk') {
                        $this->stokBarangService->tambahStok($barang, $jumlahReal);
                    } else {
                        $this->stokBarangService->kurangiStok($barang, $jumlahReal);
                    }
                }
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('transaksi.index')->with('sukses', 'Transaksi masal berhasil disimpan.');
    }

    public function show(Transaksi $transaksi): View
    {
        $transaksi->load('barang.pemasok');

        return view('transaksi.show', compact('transaksi'));
    }

    public function edit(Transaksi $transaksi): View
    {
        $daftarBarang = Barang::query()->where('status_barang', 'Aktif')->orderBy('nama_barang')->get();

        return view('transaksi.edit', compact('transaksi', 'daftarBarang'));
    }

    public function update(Request $request, Transaksi $transaksi): RedirectResponse
    {

        $data = $request->validate([
            'id_barang' => ['required', 'exists:barang,id_barang'],
            'tanggal' => ['required', 'date'],
            'jenis' => ['required', 'in:Masuk,Keluar'],
            'jumlah_input' => ['required', 'integer', 'min:1'],
            'satuan_input' => ['required', 'string'],
            'keterangan' => ['nullable', 'string', 'max:5000'],
        ]);

        try {
            DB::transaction(function () use ($data, $transaksi) {
                $lamaBarangId = $transaksi->id_barang;
                $lamaJenis = $transaksi->jenis;
                $lamaJumlah = $transaksi->jumlah;

                $barangLama = Barang::query()->lockForUpdate()->findOrFail($lamaBarangId);
                if ($lamaJenis === 'Masuk') {
                    $this->stokBarangService->kurangiStok($barangLama, $lamaJumlah);
                } else {
                    $this->stokBarangService->tambahStok($barangLama, $lamaJumlah);
                }

                $barangBaru = Barang::query()->lockForUpdate()->findOrFail($data['id_barang']);
                
                // Hitung jumlah sebenarnya (base unit)
                $jumlahReal = $data['jumlah_input'];
                if ($barangBaru->satuan_besar && $data['satuan_input'] === $barangBaru->satuan_besar) {
                    $jumlahReal = $data['jumlah_input'] * $barangBaru->isi_per_satuan_besar;
                }

                $transaksi->update([
                    'id_barang' => $data['id_barang'],
                    'tanggal' => $data['tanggal'],
                    'jenis' => $data['jenis'],
                    'jumlah' => $jumlahReal,
                    'satuan_input' => $data['satuan_input'],
                    'jumlah_input' => $data['jumlah_input'],
                    'keterangan' => $data['keterangan'] ?? null,
                ]);
                if ($data['jenis'] === 'Masuk') {
                    $this->stokBarangService->tambahStok($barangBaru, $jumlahReal);
                } else {
                    $this->stokBarangService->kurangiStok($barangBaru, $jumlahReal);
                }
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('transaksi.index')->with('sukses', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaksi $transaksi): RedirectResponse
    {

        try {
            DB::transaction(function () use ($transaksi) {
                $barang = Barang::query()->lockForUpdate()->findOrFail($transaksi->id_barang);
                if ($transaksi->jenis === 'Masuk') {
                    $this->stokBarangService->kurangiStok($barang, $transaksi->jumlah);
                } else {
                    $this->stokBarangService->tambahStok($barang, $transaksi->jumlah);
                }
                $transaksi->delete();
            });
        } catch (ValidationException $e) {
            return redirect()->route('transaksi.index')->with('error', collect($e->errors())->flatten()->first());
        }

        return redirect()->route('transaksi.index')->with('sukses', 'Transaksi berhasil dihapus.');
    }
}
