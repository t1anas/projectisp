<?php

use App\Http\Controllers\SesiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\CSController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TagihanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/',       [SesiController::class, 'index'])->name('login');
    Route::post('/',      [SesiController::class, 'login']);
    Route::get('/login',  [SesiController::class, 'index']);
    Route::post('/login', [SesiController::class, 'login']);
});

Route::get('/home', fn() => redirect('/admin'));

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*--- LOGOUT ---*/
    Route::post('/logout', [SesiController::class, 'logout'])->name('logout');

    /*--- DASHBOARD ---*/
    Route::get('/admin',       [AdminController::class, 'index']    )->middleware('userakses:admin');
    Route::get('/admin/noc',   [AdminController::class, 'noc']      )->middleware('userakses:noc');
    Route::get('/admin/admin', [AdminController::class, 'admin']    )->middleware('userakses:admin');
    Route::get('/cs/cs',       [CSController::class,   'dashboard'] )->middleware('userakses:cs');

    /*--- PEMASUKAN ---*/
    Route::get('/pemasukan', [PemasukanController::class, 'menu'])
        ->middleware('userakses:admin')
        ->name('pemasukan');

    /*--- PEMBAYARAN ---*/
    Route::prefix('pembayaran')->group(function () {
        Route::get('/',          [PemasukanController::class, 'index']  )->name('pembayaran');
        Route::post('/store',    [PemasukanController::class, 'store']  )->name('pembayaran.store');
        Route::get('/{id}/edit', [PemasukanController::class, 'edit']   )->name('pembayaran.edit');
        Route::put('/{id}',      [PemasukanController::class, 'update'] )->name('pembayaran.update');
        Route::delete('/{id}',   [PemasukanController::class, 'destroy'])->name('pembayaran.destroy');
    });

    /*--- CS ---*/
    Route::middleware('userakses:cs')->prefix('cs')->group(function () {
        Route::get('/scan',              fn() => view('scan_cs'));
        Route::post('/pembayaran/store', [PemasukanController::class, 'store']);
    });

    /*--- PELANGGAN ---*/
    Route::prefix('pelanggan')->group(function () {
        Route::get('/',                  [PelangganController::class, 'index']);
        Route::get('/{id}',              [PelangganController::class, 'show']);
        Route::post('/store',            [PelangganController::class, 'store']);
        Route::put('/{id}',              [PelangganController::class, 'update']);
        Route::delete('/{id}',           [PelangganController::class, 'destroy']);
        Route::post('/generate-tagihan', [PelangganController::class, 'generateTagihan'])
            ->name('pelanggan.generateTagihan');
    });

    Route::get('/instalasi', [PelangganController::class, 'create']);

    /*--- LAYANAN ---*/
    Route::resource('layanan', LayananController::class);
    Route::get('/layanan/{id}/detail', [DetailController::class, 'index'])->name('layanan.detail');

    /*--- TAGIHAN ---*/
    Route::post('/tagihan/{id}/bayar', [DetailController::class, 'bayar'])->name('tagihan.bayar'); // ← pakai DetailController
    Route::resource('tagihan', TagihanController::class);

});