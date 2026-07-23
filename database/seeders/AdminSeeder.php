<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Pemasok;
use App\Models\Pengguna;
use App\Models\Transaksi;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Akun default untuk pengujian:
     * Admin — email: admin@cahayamulya.test, password: password
     * Pemilik — email: pemilik@cahayamulya.test, password: password
     */
    public function run(): void
    {
        Pengguna::query()->firstOrCreate(
            ['email' => 'admin@cahayamulya.test'],
            [
                'nama_pengguna' => 'Administrator',
                'password' => 'password',
                'peran' => 'Admin',
            ]
        );

        Pengguna::query()->firstOrCreate(
            ['email' => 'pemilik@cahayamulya.test'],
            [
                'nama_pengguna' => 'Pemilik Toko',
                'password' => 'password',
                'peran' => 'Pemilik',
            ]
        );

        $pemasok = Pemasok::query()->firstOrCreate(
            ['nama_pemasok' => 'Pemasok Utama'],
            [
                'alamat' => 'Jakarta',
                'telepon' => '081234567890',
            ]
        );

        $barang = Barang::query()->firstOrCreate(
            ['nama_barang' => 'Contoh Barang A'],
            [
                'id_pemasok' => $pemasok->id_pemasok,
                'lead_time_hari' => 7,
                'lead_time_menit' => 0,
                'satuan' => 'PCS',
                'stok_saat_ini' => 100,
                'stok_minimum' => 20,
                'harga_beli' => 5000,
                'harga_jual' => 7500,
                'biaya_pesan' => 50000,
                'biaya_simpan' => 1200,
                'status_barang' => 'Aktif',
            ]
        );

        if (Transaksi::query()->where('id_barang', $barang->id_barang)->doesntExist()) {
            Transaksi::query()->create([
                'id_barang' => $barang->id_barang,
                'tanggal' => now()->subDays(10)->toDateString(),
                'jenis' => 'Keluar',
                'jumlah' => 5,
                'keterangan' => 'Penjualan contoh',
            ]);
            Transaksi::query()->create([
                'id_barang' => $barang->id_barang,
                'tanggal' => now()->subDays(5)->toDateString(),
                'jenis' => 'Keluar',
                'jumlah' => 8,
                'keterangan' => 'Penjualan contoh',
            ]);
            Transaksi::query()->create([
                'id_barang' => $barang->id_barang,
                'tanggal' => now()->subDays(1)->toDateString(),
                'jenis' => 'Masuk',
                'jumlah' => 20,
                'keterangan' => 'Restock contoh',
            ]);

            $barang->update(['stok_saat_ini' => 100 - 5 - 8 + 20]);
        }
    }
}
