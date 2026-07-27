<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope('excludeTrashedBarang', function (Builder $builder) {
            $trashedIds = \App\Models\Barang::onlyTrashed()->pluck('id_barang')->toArray();
            if (!empty($trashedIds)) {
                $builder->whereNotIn('transaksi.id_barang', $trashedIds);
            }
        });
    }

    protected $table = 'transaksi';

    protected $primaryKey = 'id_transaksi';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_transaksi',
        'id_barang',
        'tanggal',
        'jenis',
        'jumlah',
        'satuan_input',
        'jumlah_input',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah' => 'integer',
            'jumlah_input' => 'integer',
        ];
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function getRouteKeyName(): string
    {
        return 'id_transaksi';
    }
}
