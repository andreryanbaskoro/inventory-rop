<?php

namespace App\Observers;

use App\Models\Barang;
use App\Support\KodeGenerator;

class BarangObserver
{
    public function creating(Barang $barang): void
    {
        if (empty($barang->id_barang)) {
            $barang->id_barang = KodeGenerator::berikutnya(Barang::class, 'id_barang', 'BRG-');
        }
    }
}
