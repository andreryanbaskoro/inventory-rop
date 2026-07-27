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

        $perHari = Transaksi::withoutGlobalScopes()
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

        $hari = (float) ($barang->lead_time_hari ?? 1);
        $menit = (float) ($barang->lead_time_menit ?? 0);
        $leadTime = max(0.0001, $hari + ($menit / 1440));

        $ss = ceil(max(0.0, ($pemakaianMaksHarian - $pemakaianRataHarian) * $leadTime));
        $rop = ceil(($leadTime * $pemakaianRataHarian) + $ss);

        $D = ceil($pemakaianRataHarian * 365);
        
        $S = (float) $barang->biaya_pesan;
        $isAsumsiS = false;
        if ($S <= 0) {
            $S = ceil((0.05 * (float) $barang->harga_beli) / 100) * 100; // 5% dari harga beli, bulatkan ke ratusan terdekat
            if ($S <= 0) $S = 20000.0; // Fallback darurat
            $isAsumsiS = true;
        }

        $H = (float) $barang->biaya_simpan;
        $isAsumsiH = false;
        if ($H <= 0) {
            $H = ceil((0.20 * (float) $barang->harga_beli) / 100) * 100; // 20% dari harga beli, bulatkan ke ratusan terdekat
            if ($H <= 0) $H = 2000.0; // Fallback darurat jika harga beli juga 0
            $isAsumsiH = true;
        }

        $eoq = 0;
        if ($H > 0 && $D > 0 && $S > 0) {
            $eoq = ceil(sqrt((2 * $D * $S) / $H));
        }

        // Karena ROP sudah integer, cukup bandingkan langsung
        $perluReorder = $barang->stok_saat_ini <= $rop;

        return [
            'periode_hari' => $periodeHari,
            'total_keluar_periode' => $totalKeluar,
            'hari_aktif_keluar' => $hariDenganData,
            'pemakaian_rata_harian' => $pemakaianRataHarian,
            'pemakaian_maks_harian' => $pemakaianMaksHarian,
            'lead_time_hari' => $hari,
            'lead_time_menit' => $menit,
            'lead_time_desimal' => $leadTime,
            'safety_stock' => $ss,
            'rop' => $rop,
            'permintaan_tahunan' => $D,
            'eoq' => $eoq,
            'biaya_pesan_dipakai' => $S,
            'biaya_simpan_dipakai' => $H,
            'is_asumsi_s' => $isAsumsiS,
            'is_asumsi_h' => $isAsumsiH,
            'perlu_reorder' => $perluReorder,
        ];
    }

    /**
     * Optimasi performa tinggi: Menghitung ROP dan EOQ untuk banyak barang sekaligus dalam 1x query database.
     */
    public function hitungBatch(iterable $daftarBarang, int $periodeHari = 90): array
    {
        $sampai = Carbon::today();
        $dari = (clone $sampai)->subDays($periodeHari);

        $ids = [];
        foreach ($daftarBarang as $b) {
            $ids[] = $b->id_barang;
        }

        if (empty($ids)) {
            return [];
        }

        // HANYA 1x QUERY DATABASE untuk seluruh list barang!
        $transaksiGroup = Transaksi::withoutGlobalScopes()
            ->whereIn('id_barang', $ids)
            ->where('jenis', 'Keluar')
            ->whereBetween('tanggal', [$dari->toDateString(), $sampai->toDateString()])
            ->selectRaw('id_barang, DATE(tanggal) as tanggal_harian, SUM(jumlah) as total_keluar')
            ->groupBy('id_barang', 'tanggal_harian')
            ->get()
            ->groupBy('id_barang');

        $hasilBatch = [];
        foreach ($daftarBarang as $barang) {
            $dataBarang = $transaksiGroup->get($barang->id_barang) ?? collect();
            $totalKeluar = (int) $dataBarang->sum('total_keluar');
            $hariDenganData = $dataBarang->count();

            $pemakaianRataHarian = $totalKeluar / $periodeHari;
            $pemakaianMaksHarian = $dataBarang->isEmpty() ? 0.0 : (float) $dataBarang->max('total_keluar');

            $hari = (float) ($barang->lead_time_hari ?? 1);
            $menit = (float) ($barang->lead_time_menit ?? 0);
            $leadTime = max(0.0001, $hari + ($menit / 1440));

            $ss = ceil(max(0.0, ($pemakaianMaksHarian - $pemakaianRataHarian) * $leadTime));
            $rop = ceil(($leadTime * $pemakaianRataHarian) + $ss);

            $D = ceil($pemakaianRataHarian * 365);

            $S = (float) $barang->biaya_pesan;
            $isAsumsiS = false;
            if ($S <= 0) {
                $S = ceil((0.05 * (float) $barang->harga_beli) / 100) * 100;
                if ($S <= 0) $S = 20000.0;
                $isAsumsiS = true;
            }

            $H = (float) $barang->biaya_simpan;
            $isAsumsiH = false;
            if ($H <= 0) {
                $H = ceil((0.20 * (float) $barang->harga_beli) / 100) * 100;
                if ($H <= 0) $H = 2000.0;
                $isAsumsiH = true;
            }

            $eoq = 0;
            if ($H > 0 && $D > 0 && $S > 0) {
                $eoq = ceil(sqrt((2 * $D * $S) / $H));
            }

            $perluReorder = $barang->stok_saat_ini <= $rop;

            $hasilBatch[$barang->id_barang] = [
                'periode_hari' => $periodeHari,
                'total_keluar_periode' => $totalKeluar,
                'hari_aktif_keluar' => $hariDenganData,
                'pemakaian_rata_harian' => $pemakaianRataHarian,
                'pemakaian_maks_harian' => $pemakaianMaksHarian,
                'lead_time_hari' => $hari,
                'lead_time_menit' => $menit,
                'lead_time_desimal' => $leadTime,
                'safety_stock' => $ss,
                'rop' => $rop,
                'permintaan_tahunan' => $D,
                'eoq' => $eoq,
                'biaya_pesan_dipakai' => $S,
                'biaya_simpan_dipakai' => $H,
                'is_asumsi_s' => $isAsumsiS,
                'is_asumsi_h' => $isAsumsiH,
                'perlu_reorder' => $perluReorder,
            ];
        }

        return $hasilBatch;
    }
}
