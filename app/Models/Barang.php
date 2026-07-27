<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use SoftDeletes;

    protected $table = 'barang';

    protected $primaryKey = 'id_barang';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_barang',
        'id_pemasok',
        'lead_time_hari',
        'lead_time_menit',
        'nama_barang',
        'satuan',
        'satuan_besar',
        'isi_per_satuan_besar',
        'stok_saat_ini',
        'stok_minimum',
        'harga_beli',
        'harga_jual',
        'biaya_pesan',
        'biaya_simpan',
        'status_barang',
    ];

    protected function casts(): array
    {
        return [
            'lead_time_hari' => 'integer',
            'lead_time_menit' => 'integer',
            'stok_saat_ini' => 'integer',
            'stok_minimum' => 'integer',
            'isi_per_satuan_besar' => 'integer',
            'harga_beli' => 'decimal:2',
            'harga_jual' => 'decimal:2',
            'biaya_pesan' => 'decimal:2',
            'biaya_simpan' => 'decimal:2',
        ];
    }

    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class, 'id_pemasok', 'id_pemasok');
    }

    public function daftarTransaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'id_barang', 'id_barang');
    }

    public function getRouteKeyName(): string
    {
        return 'id_barang';
    }
}
