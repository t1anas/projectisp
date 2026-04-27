<?php

use App\Http\Controllers\SesiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\CSController;
use App\Http\Controllers\LayananController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH (LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/', [SesiController::class, 'index'])->name('login');
    Route::post('/', [SesiController::class, 'login']);

    Route::get('/login', [SesiController::class, 'index']);
    Route::post('/login', [SesiController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| AFTER LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    return redirect('/admin');
});

/*
|--------------------------------------------------------------------------
| PROTECTED (LOGIN WAJIB)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::middleware(['auth'])->group(function () {
    Route::get('/pemasukan', [PemasukanController::class, 'index'])
        ->middleware('role:admin');
});
    Route::get('/admin', [AdminController::class, 'index'])
        ->middleware('userakses:admin');

    Route::get('/cs/cs', [CSController::class, 'dashboard'])
        ->middleware('userakses:cs');

    Route::get('/admin/noc', [AdminController::class, 'noc'])
        ->middleware('userakses:noc');

    Route::get('/admin/admin', [AdminController::class, 'admin'])
        ->middleware('userakses:admin');

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [SesiController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | MENU CS
    |--------------------------------------------------------------------------
    */
    Route::middleware('userakses:cs')->group(function () {

        Route::get('/cs/scan', function () {
            return view('scan_cs');
        });

        Route::post('/cs/pembayaran/store', [PemasukanController::class, 'store']);
    });

    /*
    |--------------------------------------------------------------------------
    | DATA PELANGGAN
    |--------------------------------------------------------------------------
    */
    Route::get('/pelanggan', [PelangganController::class, 'index']);
    Route::get('/instalasi', [PelangganController::class, 'create']);
    Route::post('/pelanggan/store', [PelangganController::class, 'store']);
    Route::put('/pelanggan/{id}', [PelangganController::class, 'update']);
    Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | PEMASUKAN
    |--------------------------------------------------------------------------
    */
    Route::resource('pemasukan', PemasukanController::class);

    /*
    |--------------------------------------------------------------------------
    | LAYANAN
    |--------------------------------------------------------------------------
    */
    Route::resource('layanan', LayananController::class);

});
Route::get('/approve', [PelangganController::class, 'approvePage']);
Route::post('/pelanggan/{id}/approve', [PelangganController::class, 'approve'])
    ->name('pelanggan.approve');
Route::post('/pelanggan/generate-tagihan', [PelangganController::class, 'generateTagihan'])
    ->name('pelanggan.generateTagihan');