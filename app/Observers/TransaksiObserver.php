<?php

namespace App\Observers;

use App\Models\Transaksi;
use App\Support\KodeGenerator;

class TransaksiObserver
{
    public function creating(Transaksi $transaksi): void
    {
        if (empty($transaksi->id_transaksi)) {
            $transaksi->id_transaksi = KodeGenerator::berikutnya(Transaksi::class, 'id_transaksi', 'TRX-');
        }
    }
}
