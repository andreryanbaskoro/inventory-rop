<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\MengirimDataTablesJson;
use App\Models\Barang;
use App\Models\Pemasok;
use App\Models\PengadaanBarang;
use App\Models\Transaksi;
use App\Services\StokBarangService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PengadaanController extends Controller
{
    use MengirimDataTablesJson;

    public function __construct(
        protected StokBarangService $stokBarangService
    ) {}

    public static function prefixKeteranganOtomatis(string $idPengadaan): string
    {
        return '[OTOMATIS-PENGADAAN:'.$idPengadaan.']';
    }

    public function index(): View
    {
        return view('pengadaan.index');
    }

    public function data(Request $request): JsonResponse
    {
        $queryTanpa = PengadaanBarang::query()->with(['barang', 'pemasok']);

        $query = PengadaanBarang::query()->with(['barang', 'pemasok']);

        $pencarian = $request->input('search.value');
        if (is_string($pencarian) && $pencarian !== '') {
            $like = '%'.$pencarian.'%';
            $query->where(function ($q) use ($like) {
                $q->where('id_pengadaan', 'like', $like)
                    ->orWhere('status_pengadaan', 'like', $like)
                    ->orWhereHas('barang', fn ($b) => $b->where('nama_barang', 'like', $like))
                    ->orWhereHas('pemasok', fn ($p) => $p->where('nama_pemasok', 'like', $like));
            });
        }

        $urutanKolom = [
            0 => 'tanggal_pesan',
            1 => 'id_pengadaan',
            2 => 'status_pengadaan',
            3 => 'jumlah_pesan',
        ];

        return $this->responseDataTables(
            $request,
            $query,
            $queryTanpa,
            $urutanKolom,
            function (PengadaanBarang $p) {
                return [
                    'tanggal_pesan' => $p->tanggal_pesan->format('d/m/Y'),
                    'id_pengadaan' => $p->id_pengadaan,
                    'barang' => $p->barang?->nama_barang,
                    'pemasok' => $p->pemasok?->nama_pemasok,
                    'jumlah_pesan' => $p->jumlah_pesan,
                    'status_pengadaan' => $p->status_pengadaan,
                    'aksi' => view('pengadaan._aksi', ['pengadaan' => $p])->render(),
                ];
            }
        );
    }

    public function create(): View
    {
        $daftarBarang = Barang::query()->where('status_barang', 'Aktif')->orderBy('nama_barang')->get();
        $daftarPemasok = Pemasok::query()->orderBy('nama_pemasok')->get();

        return view('pengadaan.create', compact('daftarBarang', 'daftarPemasok'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_barang' => ['required', 'exists:barang,id_barang'],
            'id_pemasok' => ['required', 'exists:pemasok,id_pemasok'],
            'tanggal_pesan' => ['required', 'date'],
            'jumlah_pesan' => ['required', 'integer', 'min:1'],
            'status_pengadaan' => ['required', 'in:Dipesan,Dikirim,Selesai'],
            'tanggal_datang' => ['nullable', 'date'],
            'catatan' => ['nullable', 'string'],
        ], [], [
            'id_barang' => 'barang',
            'id_pemasok' => 'pemasok',
        ]);

        try {
            DB::transaction(function () use ($data) {
                $pengadaan = PengadaanBarang::query()->create([
                    'id_barang' => $data['id_barang'],
                    'id_pemasok' => $data['id_pemasok'],
                    'tanggal_pesan' => $data['tanggal_pesan'],
                    'tanggal_datang' => $data['tanggal_datang'] ?? null,
                    'jumlah_pesan' => $data['jumlah_pesan'],
                    'status_pengadaan' => $data['status_pengadaan'],
                    'catatan' => $data['catatan'] ?? null,
                ]);

                if ($data['status_pengadaan'] === 'Selesai') {
                    $this->selesaikanPengadaan($pengadaan);
                }
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('pengadaan.index')->with('sukses', 'Pengadaan berhasil ditambahkan.');
    }

    public function show(PengadaanBarang $pengadaan): View
    {
        $pengadaan->load(['barang', 'pemasok']);

        return view('pengadaan.show', compact('pengadaan'));
    }

    public function edit(PengadaanBarang $pengadaan): View
    {
        $daftarBarang = Barang::query()->where('status_barang', 'Aktif')->orderBy('nama_barang')->get();
        $daftarPemasok = Pemasok::query()->orderBy('nama_pemasok')->get();

        return view('pengadaan.edit', compact('pengadaan', 'daftarBarang', 'daftarPemasok'));
    }

    public function update(Request $request, PengadaanBarang $pengadaan): RedirectResponse
    {
        $data = $request->validate([
            'id_barang' => ['required', 'exists:barang,id_barang'],
            'id_pemasok' => ['required', 'exists:pemasok,id_pemasok'],
            'tanggal_pesan' => ['required', 'date'],
            'jumlah_pesan' => ['required', 'integer', 'min:1'],
            'status_pengadaan' => ['required', 'in:Dipesan,Dikirim,Selesai'],
            'tanggal_datang' => ['nullable', 'date'],
            'catatan' => ['nullable', 'string'],
        ]);

        $statusLama = $pengadaan->status_pengadaan;
        $statusBaru = $data['status_pengadaan'];

        if ($statusLama === 'Selesai' && $statusBaru === 'Selesai') {
            $pengadaan->update([
                'tanggal_datang' => $data['tanggal_datang'] ?? $pengadaan->tanggal_datang,
                'catatan' => $data['catatan'] ?? null,
            ]);

            return redirect()->route('pengadaan.index')->with('sukses', 'Catatan pengadaan diperbarui.');
        }

        try {
            DB::transaction(function () use ($data, $pengadaan, $statusLama, $statusBaru) {
                if ($statusLama === 'Selesai' && $statusBaru !== 'Selesai') {
                    $this->batalkanSelesai($pengadaan);
                }

                $pengadaan->update([
                    'id_barang' => $data['id_barang'],
                    'id_pemasok' => $data['id_pemasok'],
                    'tanggal_pesan' => $data['tanggal_pesan'],
                    'tanggal_datang' => $data['tanggal_datang'] ?? null,
                    'jumlah_pesan' => $data['jumlah_pesan'],
                    'status_pengadaan' => $statusBaru,
                    'catatan' => $data['catatan'] ?? null,
                ]);

                if ($statusLama !== 'Selesai' && $statusBaru === 'Selesai') {
                    $pengadaan->refresh();
                    $this->selesaikanPengadaan($pengadaan);
                }
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('pengadaan.index')->with('sukses', 'Pengadaan berhasil diperbarui.');
    }

    public function destroy(PengadaanBarang $pengadaan): RedirectResponse
    {
        try {
            DB::transaction(function () use ($pengadaan) {
                if ($pengadaan->status_pengadaan === 'Selesai') {
                    $this->batalkanSelesai($pengadaan);
                }
                $pengadaan->delete();
            });
        } catch (ValidationException $e) {
            return redirect()->route('pengadaan.index')->with('error', collect($e->errors())->flatten()->first());
        }

        return redirect()->route('pengadaan.index')->with('sukses', 'Pengadaan berhasil dihapus.');
    }

    protected function selesaikanPengadaan(PengadaanBarang $pengadaan): void
    {
        $prefix = self::prefixKeteranganOtomatis($pengadaan->id_pengadaan);
        $sudahAda = Transaksi::query()->where('keterangan', 'like', $prefix.'%')->exists();
        if ($sudahAda) {
            return;
        }

        $barang = Barang::query()->lockForUpdate()->findOrFail($pengadaan->id_barang);

        Transaksi::query()->create([
            'id_barang' => $pengadaan->id_barang,
            'tanggal' => $pengadaan->tanggal_datang ?? now()->toDateString(),
            'jenis' => 'Masuk',
            'jumlah' => $pengadaan->jumlah_pesan,
            'keterangan' => $prefix.($pengadaan->catatan ? ' '.$pengadaan->catatan : ''),
        ]);

        $this->stokBarangService->tambahStok($barang, $pengadaan->jumlah_pesan);
    }

    protected function batalkanSelesai(PengadaanBarang $pengadaan): void
    {
        $prefix = self::prefixKeteranganOtomatis($pengadaan->id_pengadaan);
        $transaksi = Transaksi::query()->where('keterangan', 'like', $prefix.'%')->first();
        if (! $transaksi) {
            return;
        }

        $barang = Barang::query()->lockForUpdate()->findOrFail($transaksi->id_barang);
        $this->stokBarangService->kurangiStok($barang, $transaksi->jumlah);
        $transaksi->delete();
    }
}
