<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengadaanBarang extends Model
{
    protected $table = 'pengadaan_barang';

    protected $primaryKey = 'id_pengadaan';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pengadaan',
        'id_barang',
        'id_pemasok',
        'tanggal_pesan',
        'tanggal_datang',
        'jumlah_pesan',
        'status_pengadaan',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pesan' => 'date',
            'tanggal_datang' => 'date',
            'jumlah_pesan' => 'integer',
        ];
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class, 'id_pemasok', 'id_pemasok');
    }

    public function getRouteKeyName(): string
    {
        return 'id_pengadaan';
    }
}
