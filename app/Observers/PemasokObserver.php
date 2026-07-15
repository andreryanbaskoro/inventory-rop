<?php

namespace App\Observers;

use App\Models\Pemasok;
use App\Support\KodeGenerator;

class PemasokObserver
{
    public function creating(Pemasok $pemasok): void
    {
        if (empty($pemasok->id_pemasok)) {
            $pemasok->id_pemasok = KodeGenerator::berikutnya(Pemasok::class, 'id_pemasok', 'PMS-');
        }
    }
}
