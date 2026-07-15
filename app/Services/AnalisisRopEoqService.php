<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Transaksi;
use Carbon\Carbon;

/**
 * Analisis ROP & EOQ berdasarkan transaksi keluar.
 *
 * Periode: 90 hari terakhir.
 * Pemakaian rata-rata harian = total keluar / 90.
 * Pemakaian maksimum harian = max(agregat per tanggal) dalam periode; jika tidak ada transaksi, 0.
 * Lead time (hari) = pemasok.rata_lead_time (minimal 1).
 *
 * SS = (pemakaian_maks_harian - pemakaian_rata_harian) * lead_time (tidak negatif)
 * ROP = (lead_time * pemakaian_rata_harian) + SS
 *
 * EOQ: D = permintaan tahunan = pemakaian_rata_harian * 365; S = biaya_pesan; H = biaya_simpan (per unit per tahun).
 * Jika H <= 0, EOQ tidak terdefinisi (null).
 */
class AnalisisRopEoqService
{
    public function hitungUntukBarang(Barang $barang, int $periodeHari = 90): array
    {
        $barang->loadMissing('pemasok');

        $sampai = Carbon::today();
        $dari = (clone $sampai)->subDays($periodeHari);

        $perHari = Transaksi::query()
            ->where('id_barang', $barang->id_barang)
            ->where('jenis', 'Keluar')
            ->whereBetween('tanggal', [$dari->toDateString(), $sampai->toDateString()])
            ->selectRaw('DATE(tanggal) as tanggal_harian, SUM(jumlah) as total_keluar')
            ->groupByRaw('DATE(tanggal)')
            ->get()
            ->pluck('total_keluar', 'tanggal_harian');

        $totalKeluar = (int) $perHari->sum();
        $hariDenganData = $perHari->count();

        $pemakaianRataHarian = $totalKeluar / $periodeHari;
        $pemakaianMaksHarian = $perHari->isEmpty() ? 0.0 : (float) $perHari->max();

        $leadTime = max(1, (int) ($barang->pemasok?->rata_lead_time ?? 1));

        $ss = max(0.0, ($pemakaianMaksHarian - $pemakaianRataHarian) * $leadTime);
        $rop = ($leadTime * $pemakaianRataHarian) + $ss;

        $D = $pemakaianRataHarian * 365;
        $S = (float) $barang->biaya_pesan;
        $H = (float) $barang->biaya_simpan;

        $eoq = null;
        if ($H > 0 && $D > 0 && $S > 0) {
            $eoq = sqrt((2 * $D * $S) / $H);
        }

        $perluReorder = $barang->stok_saat_ini <= $rop;

        return [
            'periode_hari' => $periodeHari,
            'total_keluar_periode' => $totalKeluar,
            'hari_aktif_keluar' => $hariDenganData,
            'pemakaian_rata_harian' => $pemakaianRataHarian,
            'pemakaian_maks_harian' => $pemakaianMaksHarian,
            'lead_time' => $leadTime,
            'safety_stock' => $ss,
            'rop' => $rop,
            'eoq' => $eoq,
            'perlu_reorder' => $perluReorder,
        ];
    }
}
