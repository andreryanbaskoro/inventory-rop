<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Services\AnalisisRopEoqService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalisisController extends Controller
{
    public function index(Request $request): View
    {
        $periodeHari = (int) $request->get('periode', 90);
        $layanan = app(AnalisisRopEoqService::class);

        $daftarBarang = Barang::query()
            ->with('pemasok')
            ->where('status_barang', 'Aktif')
            ->orderBy('nama_barang')
            ->get()
            ->map(function (Barang $barang) use ($layanan, $periodeHari) {
                return [
                    'barang' => $barang,
                    'analisis' => $layanan->hitungUntukBarang($barang, $periodeHari),
                ];
            });

        return view('analisis.index', compact('daftarBarang', 'periodeHari'));
    }

    public function show(Request $request, Barang $barang): View
    {
        $periodeHari = (int) $request->get('periode', 90);
        $barang->load('pemasok');
        $analisis = app(AnalisisRopEoqService::class)->hitungUntukBarang($barang, $periodeHari);

        return view('analisis.show', compact('barang', 'analisis', 'periodeHari'));
    }
}
