<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\PenggunaFactory> */
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    protected $primaryKey = 'id_pengguna';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pengguna',
        'nama_pengguna',
        'email',
        'password',
        'peran',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getAuthIdentifierName(): string
    {
        return 'id_pengguna';
    }

    public function isAdmin(): bool
    {
        return $this->peran === 'Admin';
    }

    public function isPemilik(): bool
    {
        return $this->peran === 'Pemilik';
    }
}
