<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pemasok extends Model
{
    protected $table = 'pemasok';

    protected $primaryKey = 'id_pemasok';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pemasok',
        'nama_pemasok',
        'alamat',
        'telepon',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function daftarBarang(): HasMany
    {
        return $this->hasMany(Barang::class, 'id_pemasok', 'id_pemasok');
    }

    public function getRouteKeyName(): string
    {
        return 'id_pemasok';
    }
}
