<?php

namespace App\Observers;

use App\Models\Pengguna;
use App\Support\KodeGenerator;

class PenggunaObserver
{
    public function creating(Pengguna $pengguna): void
    {
        if (empty($pengguna->id_pengguna)) {
            $pengguna->id_pengguna = KodeGenerator::berikutnya(Pengguna::class, 'id_pengguna', 'USR-');
        }
    }
}
