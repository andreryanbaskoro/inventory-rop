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

        $daftarBarangMentah = Barang::query()
            ->with('pemasok')
            ->where('status_barang', 'Aktif')
            ->orderBy('nama_barang')
            ->get();

        $hasilBatch = $layanan->hitungBatch($daftarBarangMentah, $periodeHari);

        $daftarBarang = $daftarBarangMentah->map(function (Barang $barang) use ($hasilBatch) {
            return [
                'barang' => $barang,
                'analisis' => $hasilBatch[$barang->id_barang],
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
