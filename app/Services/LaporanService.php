<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\PengadaanBarang;
use App\Models\Transaksi;
use Carbon\Carbon;
class LaporanService
{
    public const JENIS = [
        'stok' => 'Laporan Stok Barang',
        'transaksi_masuk' => 'Laporan Transaksi Masuk',
        'transaksi_keluar' => 'Laporan Transaksi Keluar',
        'pengadaan' => 'Laporan Pengadaan',
        'ringkasan' => 'Laporan Ringkasan Inventaris',
    ];

    public function labelJenis(string $jenis): string
    {
        return self::JENIS[$jenis] ?? 'Laporan';
    }

    /**
     * @return array{judul: string, kolom: list<string>, baris: list<list<string|int|float>>, ringkasan?: array<string, string|int>}
     */
    public function ambilData(string $jenis, ?string $dari = null, ?string $sampai = null): array
    {
        return match ($jenis) {
            'transaksi_masuk' => $this->laporanTransaksi($dari, $sampai, 'Masuk'),
            'transaksi_keluar' => $this->laporanTransaksi($dari, $sampai, 'Keluar'),
            'pengadaan' => $this->laporanPengadaan($dari, $sampai),
            'ringkasan' => $this->laporanRingkasan($dari, $sampai),
            default => $this->laporanStok(),
        };
    }

    /**
     * @return array{judul: string, kolom: list<string>, baris: list<list<string|int|float>>}
     */
    protected function laporanStok(): array
    {
        $analisis = app(AnalisisRopEoqService::class);

        $baris = Barang::query()
            ->with('pemasok')
            ->where('status_barang', 'Aktif')
            ->orderBy('nama_barang')
            ->get()
            ->map(function (Barang $barang) use ($analisis) {
                $hasil = $analisis->hitungUntukBarang($barang);
                $nilaiStok = (float) $barang->stok_saat_ini * (float) $barang->harga_beli;

                return [
                    $barang->id_barang,
                    $barang->nama_barang,
                    $barang->pemasok?->nama_pemasok ?? '—',
                    $barang->satuan,
                    $barang->stok_saat_ini,
                    $barang->stok_minimum,
                    number_format($hasil['rop'], 2, ',', '.'),
                    $hasil['perlu_reorder'] ? 'REORDER' : 'Aman',
                    number_format($barang->harga_beli, 0, ',', '.'),
                    number_format($nilaiStok, 0, ',', '.'),
                ];
            })
            ->values()
            ->all();

        return [
            'judul' => self::JENIS['stok'],
            'kolom' => [
                'ID',
                'Nama Barang',
                'Pemasok',
                'Satuan',
                'Stok',
                'Stok Min.',
                'ROP',
                'Status',
                'Harga Beli',
                'Nilai Stok',
            ],
            'baris' => $baris,
        ];
    }

    /**
     * @return array{judul: string, kolom: list<string>, baris: list<list<string|int|float>>}
     */
    protected function laporanTransaksi(?string $dari, ?string $sampai, string $jenisTransaksi): array
    {
        [$awal, $akhir] = $this->rentangTanggal($dari, $sampai);

        $baris = Transaksi::query()
            ->with(['barang.pemasok'])
            ->where('jenis', $jenisTransaksi)
            ->whereBetween('tanggal', [$awal->toDateString(), $akhir->toDateString()])
            ->orderByDesc('tanggal')
            ->orderBy('id_transaksi')
            ->get()
            ->map(function (Transaksi $t) {
                $jumlahStr = $t->jumlah . ' ' . ($t->barang?->satuan ?? '');
                if ($t->satuan_input && $t->satuan_input !== $t->barang?->satuan) {
                    $jumlahStr = $t->jumlah_input . ' ' . $t->satuan_input . ' (' . $jumlahStr . ')';
                }

                return [
                    $t->id_transaksi,
                    $t->tanggal->format('d/m/Y'),
                    $t->barang?->nama_barang ?? '—',
                    $t->jenis,
                    $jumlahStr,
                    $t->keterangan ?? '—',
                ];
            })
            ->values()
            ->all();

        return [
            'judul' => self::JENIS['transaksi_' . strtolower($jenisTransaksi)],
            'kolom' => ['ID', 'Tanggal', 'Barang', 'Jenis', 'Jumlah', 'Keterangan'],
            'baris' => $baris,
            'periode' => $this->formatPeriode($awal, $akhir),
        ];
    }

    /**
     * @return array{judul: string, kolom: list<string>, baris: list<list<string|int|float>>}
     */
    protected function laporanPengadaan(?string $dari, ?string $sampai): array
    {
        [$awal, $akhir] = $this->rentangTanggal($dari, $sampai);

        $baris = PengadaanBarang::query()
            ->with(['barang', 'pemasok'])
            ->whereBetween('tanggal_pesan', [$awal->toDateString(), $akhir->toDateString()])
            ->orderByDesc('tanggal_pesan')
            ->get()
            ->map(function (PengadaanBarang $p) {
                $jumlahStr = $p->jumlah_pesan . ' ' . ($p->barang?->satuan ?? '');
                if ($p->satuan_pesan_input && $p->satuan_pesan_input !== $p->barang?->satuan) {
                    $jumlahStr = $p->jumlah_pesan_input . ' ' . $p->satuan_pesan_input . ' (' . $jumlahStr . ')';
                }

                return [
                    $p->id_pengadaan,
                    $p->tanggal_pesan->format('d/m/Y'),
                    $p->tanggal_datang?->format('d/m/Y') ?? '—',
                    $p->barang?->nama_barang ?? '—',
                    $p->pemasok?->nama_pemasok ?? '—',
                    $jumlahStr,
                    $p->status_pengadaan,
                    $p->catatan ?? '—',
                ];
            })
            ->values()
            ->all();

        return [
            'judul' => self::JENIS['pengadaan'],
            'kolom' => [
                'ID',
                'Tgl Pesan',
                'Tgl Datang',
                'Barang',
                'Pemasok',
                'Jumlah',
                'Status',
                'Catatan',
            ],
            'baris' => $baris,
            'periode' => $this->formatPeriode($awal, $akhir),
        ];
    }

    /**
     * @return array{judul: string, kolom: list<string>, baris: list<list<string|int|float>>, ringkasan: array<string, string|int>}
     */
    protected function laporanRingkasan(?string $dari, ?string $sampai): array
    {
        [$awal, $akhir] = $this->rentangTanggal($dari, $sampai);

        $totalBarangAktif = Barang::query()->where('status_barang', 'Aktif')->count();
        $stokKritis = Barang::query()
            ->where('status_barang', 'Aktif')
            ->whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->count();

        $masuk = (int) Transaksi::query()
            ->where('jenis', 'Masuk')
            ->whereBetween('tanggal', [$awal->toDateString(), $akhir->toDateString()])
            ->sum('jumlah');

        $keluar = (int) Transaksi::query()
            ->where('jenis', 'Keluar')
            ->whereBetween('tanggal', [$awal->toDateString(), $akhir->toDateString()])
            ->sum('jumlah');

        $pengadaanSelesai = PengadaanBarang::query()
            ->where('status_pengadaan', 'Selesai')
            ->whereBetween('tanggal_pesan', [$awal->toDateString(), $akhir->toDateString()])
            ->count();

        $nilaiInventaris = Barang::query()
            ->where('status_barang', 'Aktif')
            ->get()
            ->sum(fn (Barang $b) => (float) $b->stok_saat_ini * (float) $b->harga_beli);

        $ringkasan = [
            'Periode' => $this->formatPeriode($awal, $akhir),
            'Barang aktif' => $totalBarangAktif,
            'Barang stok kritis' => $stokKritis,
            'Total masuk (unit)' => $masuk,
            'Total keluar (unit)' => $keluar,
            'Pengadaan selesai' => $pengadaanSelesai,
            'Nilai inventaris (Rp)' => number_format($nilaiInventaris, 0, ',', '.'),
        ];

        $baris = collect($ringkasan)
            ->map(fn ($nilai, $kunci) => [$kunci, (string) $nilai])
            ->values()
            ->all();

        return [
            'judul' => self::JENIS['ringkasan'],
            'kolom' => ['Indikator', 'Nilai'],
            'baris' => $baris,
            'ringkasan' => $ringkasan,
            'periode' => $this->formatPeriode($awal, $akhir),
        ];
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    protected function rentangTanggal(?string $dari, ?string $sampai): array
    {
        $akhir = $sampai ? Carbon::parse($sampai) : Carbon::today();
        $awal = $dari ? Carbon::parse($dari) : (clone $akhir)->subDays(30);

        if ($awal->gt($akhir)) {
            [$awal, $akhir] = [$akhir, $awal];
        }

        return [$awal->startOfDay(), $akhir->endOfDay()];
    }

    protected function formatPeriode(Carbon $awal, Carbon $akhir): string
    {
        return $awal->format('d/m/Y').' — '.$akhir->format('d/m/Y');
    }
}
