<?php

use App\Http\Controllers\SesiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\CSController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TagihanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/',      [SesiController::class, 'index'])->name('login');
    Route::post('/',     [SesiController::class, 'login']);
    Route::get('/login', [SesiController::class, 'index']);
    Route::post('/login',[SesiController::class, 'login']);
});

Route::get('/home', fn() => redirect('/admin'));

Route::middleware(['auth'])->group(function () {

    /*--- LOGOUT ---*/
    Route::post('/logout', [SesiController::class, 'logout'])->name('logout');

    /*--- DASHBOARD ---*/
    Route::get('/admin',      [AdminController::class, 'index'])    ->middleware('userakses:admin');
    Route::get('/admin/noc',  [AdminController::class, 'noc'])      ->middleware('userakses:noc');
    Route::get('/admin/admin',[AdminController::class, 'admin'])    ->middleware('userakses:admin');
    Route::get('/cs/cs',      [CSController::class, 'dashboard'])->middleware('userakses:cs');

Route::get('/pemasukan', [PemasukanController::class, 'menu'])
    ->middleware('userakses:admin')
    ->name('pemasukan');

    // halaman pembayaran
    Route::get('/pembayaran', [PemasukanController::class, 'index'])
        ->name('pembayaran');
    Route::delete('/pembayaran/{id}', [PemasukanController::class, 'destroy'])
        ->name('pembayaran.destroy');
    Route::get('/pembayaran/{id}/edit', [PemasukanController::class, 'edit'])
        ->name('pembayaran.edit');
    Route::put('/pembayaran/{id}', [PemasukanController::class, 'update'])
        ->name('pembayaran.update');
    route::post('/pembayaran/store', [PemasukanController::class, 'store'])
        ->name('pembayaran.store');

    /*--- MENU CS ---*/
    Route::middleware('userakses:cs')->group(function () {
        Route::get('/cs/scan', fn() => view('scan_cs'));
        Route::post('/cs/pembayaran/store', [PemasukanController::class, 'store']);
    });

    /*--- DATA PELANGGAN ---*/
    Route::get('/pelanggan',         [PelangganController::class, 'index']);
    Route::get('/pelanggan/{id}',    [PelangganController::class, 'show']);
    Route::get('/instalasi',         [PelangganController::class, 'create']);
    Route::post('/pelanggan/store',  [PelangganController::class, 'store']);
    Route::put('/pelanggan/{id}',    [PelangganController::class, 'update']);
    Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy']);

    Route::post('/pelanggan/generate-tagihan', [PelangganController::class, 'generateTagihan'])
        ->name('pelanggan.generateTagihan');

    /*--- LAYANAN ---*/
    Route::resource('layanan', LayananController::class);
    Route::get('/layanan/{id}/detail', [LayananController::class, 'detail'])
        ->name('layanan.detail');

    /*--- TAGIHAN ---*/
    Route::get('/tagihan/{id}/kwitansi', [TagihanController::class, 'kwitansi'])
        ->name('tagihan.kwitansi');
    Route::get('/tagihan/{id}/bayar',    [TagihanController::class, 'bayar'])
        ->name('tagihan.bayar');
    Route::delete('/tagihan/{id}',       [TagihanController::class, 'destroy'])
        ->name('tagihan.destroy');
    Route::put('/tagihan/{id}', [TagihanController::class, 'update'])
        ->name('tagihan.update');
    Route::get('/tagihan', [TagihanController::class, 'index'])
        ->name('tagihan');
});