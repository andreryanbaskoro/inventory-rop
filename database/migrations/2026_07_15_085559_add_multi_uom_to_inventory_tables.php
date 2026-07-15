<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->string('satuan_besar', 30)->nullable()->after('satuan');
            $table->integer('isi_per_satuan_besar')->nullable()->after('satuan_besar');
        });

        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('satuan_input', 30)->nullable()->after('jumlah');
            $table->integer('jumlah_input')->nullable()->after('satuan_input');
        });

        Schema::table('pengadaan_barang', function (Blueprint $table) {
            $table->string('satuan_pesan_input', 30)->nullable()->after('jumlah_pesan');
            $table->integer('jumlah_pesan_input')->nullable()->after('satuan_pesan_input');
        });
    }

    public function down(): void
    {
        Schema::table('pengadaan_barang', function (Blueprint $table) {
            $table->dropColumn(['satuan_pesan_input', 'jumlah_pesan_input']);
        });

        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['satuan_input', 'jumlah_input']);
        });

        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['satuan_besar', 'isi_per_satuan_besar']);
        });
    }
};
