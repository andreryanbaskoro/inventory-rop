<?php

namespace App\Observers;

use App\Models\PengadaanBarang;
use App\Support\KodeGenerator;

class PengadaanObserver
{
    public function creating(PengadaanBarang $pengadaan): void
    {
        if (empty($pengadaan->id_pengadaan)) {
            $pengadaan->id_pengadaan = KodeGenerator::berikutnya(PengadaanBarang::class, 'id_pengadaan', 'PNG-');
        }
    }
}
