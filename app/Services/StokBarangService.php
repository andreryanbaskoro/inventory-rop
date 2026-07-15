<?php

namespace App\Services;

use App\Models\Barang;
use Illuminate\Validation\ValidationException;

class StokBarangService
{
    public function tambahStok(Barang $barang, int $jumlah): void
    {
        if ($jumlah < 1) {
            throw ValidationException::withMessages([
                'jumlah' => 'Jumlah harus minimal 1.',
            ]);
        }
        $barang->increment('stok_saat_ini', $jumlah);
    }

    public function kurangiStok(Barang $barang, int $jumlah): void
    {
        if ($jumlah < 1) {
            throw ValidationException::withMessages([
                'jumlah' => 'Jumlah harus minimal 1.',
            ]);
        }
        if ($barang->stok_saat_ini < $jumlah) {
            throw ValidationException::withMessages([
                'jumlah' => 'Stok tidak mencukupi. Stok saat ini: '.$barang->stok_saat_ini,
            ]);
        }
        $barang->decrement('stok_saat_ini', $jumlah);
    }
}
