<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

class KodeGenerator
{
    /**
     * Menghasilkan ID berikutnya dengan format PREFIX-NNN (nomor 3 digit).
     *
     * @param  class-string<Model>  $modelClass
     */
    public static function berikutnya(string $modelClass, string $primaryKeyColumn, string $prefix): string
    {
        $maks = $modelClass::query()
            ->where($primaryKeyColumn, 'like', $prefix.'%')
            ->pluck($primaryKeyColumn)
            ->map(function (string $nilai) use ($prefix) {
                return (int) substr($nilai, strlen($prefix));
            })
            ->max();

        $berikutnya = ($maks ?? 0) + 1;

        return $prefix.str_pad((string) $berikutnya, 3, '0', STR_PAD_LEFT);
    }
}
