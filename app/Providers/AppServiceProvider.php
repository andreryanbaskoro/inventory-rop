<?php

namespace App\Providers;

use App\Models\Barang;
use App\Models\Pemasok;
use App\Models\PengadaanBarang;
use App\Models\Pengguna;
use App\Models\Transaksi;
use App\Observers\BarangObserver;
use App\Observers\PemasokObserver;
use App\Observers\PenggunaObserver;
use App\Observers\TransaksiObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Carbon::setLocale(config('app.locale', 'id'));

        Pengguna::observe(PenggunaObserver::class);
        Pemasok::observe(PemasokObserver::class);
        Barang::observe(BarangObserver::class);
        Transaksi::observe(TransaksiObserver::class);
    }
}
