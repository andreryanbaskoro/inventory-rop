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
        $layanan = app(AnalisisRopEoqService::class);

        $daftarBarang = Barang::query()
            ->with('pemasok')
            ->where('status_barang', 'Aktif')
            ->orderBy('nama_barang')
            ->get()
            ->map(function (Barang $barang) use ($layanan) {
                return [
                    'barang' => $barang,
                    'analisis' => $layanan->hitungUntukBarang($barang),
                ];
            });

        return view('analisis.index', compact('daftarBarang'));
    }

    public function show(Barang $barang): View
    {
        $barang->load('pemasok');
        $analisis = app(AnalisisRopEoqService::class)->hitungUntukBarang($barang);

        return view('analisis.show', compact('barang', 'analisis'));
    }
}
