<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->integer('lead_time_hari')->default(1)->after('id_pemasok');
            $table->integer('lead_time_menit')->default(0)->after('lead_time_hari');
        });

        DB::statement('UPDATE barang SET lead_time_hari = (SELECT rata_lead_time FROM pemasok WHERE pemasok.id_pemasok = barang.id_pemasok), lead_time_menit = (SELECT rata_lead_time_menit FROM pemasok WHERE pemasok.id_pemasok = barang.id_pemasok) WHERE EXISTS (SELECT 1 FROM pemasok WHERE pemasok.id_pemasok = barang.id_pemasok)');

        Schema::table('pemasok', function (Blueprint $table) {
            $table->dropColumn(['rata_lead_time', 'rata_lead_time_menit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemasok', function (Blueprint $table) {
            $table->integer('rata_lead_time')->default(1);
            $table->integer('rata_lead_time_menit')->default(0);
        });

        DB::statement('UPDATE pemasok SET rata_lead_time = (SELECT lead_time_hari FROM barang WHERE barang.id_pemasok = pemasok.id_pemasok LIMIT 1), rata_lead_time_menit = (SELECT lead_time_menit FROM barang WHERE barang.id_pemasok = pemasok.id_pemasok LIMIT 1) WHERE EXISTS (SELECT 1 FROM barang WHERE barang.id_pemasok = pemasok.id_pemasok)');

        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['lead_time_hari', 'lead_time_menit']);
        });
    }
};
