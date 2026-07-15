<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExcelExporter;
use App\Services\LaporanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index(Request $request, LaporanService $layanan): View
    {
        $jenis = $this->validasiJenis($request->query('jenis', 'stok'));
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');

        if (! $dari && ! $sampai && $jenis !== 'stok') {
            $sampai = Carbon::today()->toDateString();
            $dari = Carbon::today()->subDays(30)->toDateString();
        }

        $data = $layanan->ambilData($jenis, $dari, $sampai);

        return view('laporan.index', [
            'jenis' => $jenis,
            'daftarJenis' => LaporanService::JENIS,
            'dari' => $dari,
            'sampai' => $sampai,
            'data' => $data,
            'periode' => $data['periode'] ?? null,
        ]);
    }

    public function excel(Request $request, LaporanService $layanan, LaporanExcelExporter $exporter): StreamedResponse
    {
        $jenis = $this->validasiJenis($request->query('jenis', 'stok'));
        $data = $layanan->ambilData($jenis, $request->query('dari'), $request->query('sampai'));
        $namaBerkas = 'laporan-'.$jenis.'-'.now()->format('Ymd-His').'.xlsx';

        return $exporter->unduh($data, $namaBerkas);
    }

    public function pdf(Request $request, LaporanService $layanan): Response
    {
        $jenis = $this->validasiJenis($request->query('jenis', 'stok'));
        $data = $layanan->ambilData($jenis, $request->query('dari'), $request->query('sampai'));

        $pdf = Pdf::loadView('laporan.cetak', [
            'data' => $data,
            'periode' => $data['periode'] ?? null,
            'dicetakPada' => now()->timezone('Asia/Jakarta')->format('d/m/Y H:i'),
            'namaToko' => 'Cahaya Mulya Mart',
        ])->setPaper('a4', 'landscape');

        $namaBerkas = 'laporan-'.$jenis.'-'.now()->format('Ymd-His').'.pdf';

        return $pdf->download($namaBerkas);
    }

    protected function validasiJenis(string $jenis): string
    {
        return array_key_exists($jenis, LaporanService::JENIS) ? $jenis : 'stok';
    }
}
