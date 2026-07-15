<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pemasok;
use App\Models\Transaksi;
use App\Services\AnalisisRopEoqService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        Carbon::setLocale('id');

        /** @var \App\Models\Pengguna $pengguna */
        $pengguna = $request->user();

        $totalBarang = Barang::query()->where('status_barang', 'Aktif')->count();
        $totalPemasok = Pemasok::query()->count();
        $totalTransaksiMasuk = Transaksi::query()->where('jenis', 'Masuk')->count();
        $totalTransaksiKeluar = Transaksi::query()->where('jenis', 'Keluar')->count();

        $jumlahMasuk = (int) Transaksi::query()->where('jenis', 'Masuk')->sum('jumlah');
        $jumlahKeluar = (int) Transaksi::query()->where('jenis', 'Keluar')->sum('jumlah');

        $grafikLabel = [];
        $grafikMasuk = [];
        $grafikKeluar = [];

        for ($i = 5; $i >= 0; $i--) {
            $awal = now()->subMonths($i)->startOfMonth();
            $akhir = (clone $awal)->endOfMonth();
            $grafikLabel[] = $awal->translatedFormat('M Y');

            $grafikMasuk[] = (int) Transaksi::query()
                ->where('jenis', 'Masuk')
                ->whereBetween('tanggal', [$awal->toDateString(), $akhir->toDateString()])
                ->sum('jumlah');

            $grafikKeluar[] = (int) Transaksi::query()
                ->where('jenis', 'Keluar')
                ->whereBetween('tanggal', [$awal->toDateString(), $akhir->toDateString()])
                ->sum('jumlah');
        }

        $barangStokKritis = Barang::query()
            ->with('pemasok')
            ->where('status_barang', 'Aktif')
            ->whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->orderBy('stok_saat_ini')
            ->limit(15)
            ->get();

        $analisisService = app(AnalisisRopEoqService::class);
        $peringatanReorder = collect();

        if ($pengguna->isAdmin()) {
            $peringatanReorder = Barang::query()
                ->with('pemasok')
                ->where('status_barang', 'Aktif')
                ->get()
                ->map(function (Barang $barang) use ($analisisService) {
                    $hasil = $analisisService->hitungUntukBarang($barang);

                    return [
                        'barang' => $barang,
                        'rop' => $hasil['rop'],
                        'perlu_reorder' => $hasil['perlu_reorder'],
                    ];
                })
                ->filter(fn (array $row) => $row['perlu_reorder'])
                ->take(10);
        }

        return view('dashboard.index', compact(
            'pengguna',
            'totalBarang',
            'totalPemasok',
            'totalTransaksiMasuk',
            'totalTransaksiKeluar',
            'jumlahMasuk',
            'jumlahKeluar',
            'grafikLabel',
            'grafikMasuk',
            'grafikKeluar',
            'barangStokKritis',
            'peringatanReorder'
        ));
    }
}
