<?php

use App\Http\Controllers\AnalisisController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard.index')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'formLogin'])->name('login');
    Route::post('login', [LoginController::class, 'prosesLogin'])->name('login.proses');
});

Route::post('keluar', [LoginController::class, 'keluar'])
    ->middleware('auth')
    ->name('keluar');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('barang/data', [BarangController::class, 'data'])->name('barang.data');
    Route::resource('barang', BarangController::class)->except(['show']);

    Route::get('pemasok/data', [PemasokController::class, 'data'])->name('pemasok.data');
    Route::get('pemasok/{pemasok}/barang', [PemasokController::class, 'barang'])->name('pemasok.barang');
    Route::resource('pemasok', PemasokController::class);

    Route::get('transaksi/data', [TransaksiController::class, 'data'])->name('transaksi.data');
    Route::resource('transaksi', TransaksiController::class);

    Route::get('backup', [BackupController::class, 'index'])->name('backup.index');
    Route::get('backup/download', [BackupController::class, 'download'])->name('backup.download');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('analisis', [AnalisisController::class, 'index'])->name('analisis.index');
    Route::get('analisis/{barang}', [AnalisisController::class, 'show'])->name('analisis.show');

    Route::get('barang/{barang}', [BarangController::class, 'show'])->name('barang.show');
});

Route::middleware(['auth', 'pemilik'])->prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');
    Route::get('export/excel', [LaporanController::class, 'excel'])->name('excel');
    Route::get('export/pdf', [LaporanController::class, 'pdf'])->name('pdf');
});
