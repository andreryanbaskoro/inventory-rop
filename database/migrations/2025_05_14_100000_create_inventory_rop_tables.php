<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->string('id_pengguna', 20)->primary();
            $table->string('nama_pengguna', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->enum('peran', ['Admin', 'Pemilik'])->default('Admin');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('pemasok', function (Blueprint $table) {
            $table->string('id_pemasok', 20)->primary();
            $table->string('nama_pemasok', 100);
            $table->text('alamat')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->integer('rata_lead_time')->default(1);
            $table->timestamps();
        });

        Schema::create('barang', function (Blueprint $table) {
            $table->string('id_barang', 20)->primary();
            $table->string('id_pemasok', 20);
            $table->string('nama_barang', 150);
            $table->string('satuan', 30)->default('PCS');
            $table->integer('stok_saat_ini')->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->decimal('biaya_pesan', 15, 2)->default(0);
            $table->decimal('biaya_simpan', 15, 2)->default(0);
            $table->enum('status_barang', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->timestamps();

            $table->foreign('id_pemasok')->references('id_pemasok')->on('pemasok');
            $table->index('nama_barang', 'idx_barang_nama');
        });

        Schema::create('transaksi', function (Blueprint $table) {
            $table->string('id_transaksi', 20)->primary();
            $table->string('id_barang', 20);
            $table->date('tanggal');
            $table->enum('jenis', ['Masuk', 'Keluar']);
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_barang')->references('id_barang')->on('barang');
            $table->index('tanggal', 'idx_transaksi_tanggal');
            $table->index('jenis', 'idx_transaksi_jenis');
        });

        Schema::create('pengadaan_barang', function (Blueprint $table) {
            $table->string('id_pengadaan', 20)->primary();
            $table->string('id_barang', 20);
            $table->string('id_pemasok', 20);
            $table->date('tanggal_pesan');
            $table->date('tanggal_datang')->nullable();
            $table->integer('jumlah_pesan');
            $table->enum('status_pengadaan', ['Dipesan', 'Dikirim', 'Selesai'])->default('Dipesan');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_barang')->references('id_barang')->on('barang');
            $table->foreign('id_pemasok')->references('id_pemasok')->on('pemasok');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengadaan_barang');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('pemasok');
        Schema::dropIfExists('pengguna');
    }
};
