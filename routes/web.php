<?php

use App\Http\Controllers\SesiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\CSController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\ApprovePelangganController;
use App\Http\Controllers\PelangganDetailController;
use App\Http\Controllers\InstalasinocController;
use App\Http\Controllers\AgendaNocController;
use App\Http\Controllers\ScanController;
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
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/agenda-noc', [AgendaNocController::class, 'index'])->name('agenda-noc.index');
    Route::patch('/agenda-noc/{id}/approve', [AgendaNocController::class, 'approve'])->name('agendanoc.approve');
    Route::patch('/agenda-noc/{id}/reject',  [AgendaNocController::class, 'reject'])->name('agendanoc.reject');

    /* LOGOUT */
    Route::post('/logout', [SesiController::class, 'logout'])->name('logout');

    /* DASHBOARD */
    Route::get('/admin',       [AdminController::class, 'index'])->middleware('userakses:admin');
    Route::get('/noc/noc',     [AdminController::class, 'noc'])->middleware('userakses:noc');
    Route::get('/admin/admin', [AdminController::class, 'admin'])->middleware('userakses:admin');
    Route::get('/cs/cs',       [CSController::class, 'dashboard'])->middleware('userakses:cs');

    /* PEMASUKAN */
    Route::get('/pemasukan', [PemasukanController::class, 'menu'])
        ->middleware('userakses:admin')
        ->name('pemasukan');

    /* PEMBAYARAN */
    Route::prefix('pembayaran')->group(function () {
        Route::get('/',          [PemasukanController::class, 'index'])->name('pembayaran');
        Route::post('/store',    [PemasukanController::class, 'store'])->name('pembayaran.store');
        Route::get('/{id}/edit', [PemasukanController::class, 'edit'])->name('pembayaran.edit');
        Route::put('/{id}',      [PemasukanController::class, 'update'])->name('pembayaran.update');
        Route::delete('/{id}',   [PemasukanController::class, 'destroy'])->name('pembayaran.destroy');
    });

    /* CS */
    Route::middleware('userakses:cs')->prefix('cs')->group(function () {
        Route::get('/scan',              fn() => view('scan_cs'));
        Route::post('/pembayaran/store', [PemasukanController::class, 'store']);
    });

/* PELANGGAN */
Route::prefix('pelanggan')->group(function () {
    Route::get('/',                      [PelangganController::class, 'index']);
    Route::post('/store',                [PelangganController::class, 'store']);
    Route::put('/{id}',                  [PelangganController::class, 'update']);
    Route::delete('/{id}',               [PelangganController::class, 'destroy']);
    Route::post('/generate-tagihan',     [PelangganController::class, 'generateTagihan'])->name('pelanggan.generateTagihan');
    Route::post('/generate-terpilih',    [PelangganController::class, 'generateTagihanTerpilih'])->name('pelanggan.generateTagihanTerpilih'); // ← tambah ini
    Route::get('/detail/{id}',           [PelangganDetailController::class, 'show']);
});

    Route::get('/instalasi', [PelangganController::class, 'create']);

    /* LAYANAN */
    Route::get('/layanan',              [LayananController::class, 'index']);
    Route::get('/layanan/export',       [LayananController::class, 'export'])->name('layanan.export');
    Route::get('/layanan/cetak',        [LayananController::class, 'cetak'])->name('layanan.cetak');
    Route::post('/layanan/bulk-delete', [LayananController::class, 'bulkDelete'])->name('layanan.bulkDelete');
    Route::post('/layanan/bulk-status', [LayananController::class, 'bulkStatus'])->name('layanan.bulkStatus');
    Route::post('/layanan/bulk-isolir', [LayananController::class, 'bulkIsolir'])->name('layanan.bulkIsolir');
    Route::post('/layanan/bulk-aktivasi', [LayananController::class, 'bulkAktivasi'])->name('layanan.bulkAktivasi');
    Route::put('/layanan/{id}',         [LayananController::class, 'update']);
    Route::post('/layanan/{id}/bayar',  [DetailController::class, 'bayar'])->name('tagihan.bayar');
    Route::get('/layanan/{id}',         [DetailController::class, 'index'])->name('layanan.detail');
    Route::post('/layanan/import', [LayananController::class, 'import'])->name('layanan.import');

    /* TAGIHAN */
    Route::resource('tagihan', TagihanController::class);
    Route::post('/tagihan/{id}/bayar',    [TagihanController::class, 'bayar'])->name('tagihan.bayar');
    Route::get('/tagihan/{id}/kwitansi',  [KwitansiController::class, 'cetak'])->name('kwitansi');

});

/* BAYAR LAYANAN */
Route::post('/layanan/{id}/bayar', [DetailController::class, 'bayar'])->name('detail.bayar');

/* APPROVE */
Route::middleware('userakses:admin')->prefix('approve')->group(function () {
    Route::get('/',                   [ApprovePelangganController::class, 'index']);
    Route::post('/{id}/approve',      [ApprovePelangganController::class, 'approve']);
    Route::post('/{id}/reject',       [ApprovePelangganController::class, 'reject']);
    Route::post('/bulk-approve',      [ApprovePelangganController::class, 'bulkApprove']);
    Route::post('/bulk-reject',       [ApprovePelangganController::class, 'bulkReject']);
    Route::get('/pelanggan/{id}',     [PelangganController::class, 'show']);
});

/* INSTALASI NOC */
Route::get('/instalasi-noc', [InstalasiNocController::class, 'index'])->name('instalasi-noc');

Route::prefix('instalasi-noc')
    ->name('instalasi-noc.')
    ->middleware(['auth', 'userakses:noc'])
    ->group(function () {
        Route::get('/{id}',          [InstalasiNocController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [InstalasiNocController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject',  [InstalasiNocController::class, 'reject'])->name('reject');
    });

/* PELANGGAN PUBLIK */
Route::get('/pelanggan/{kode}', [PelangganController::class, 'detail']);

/* SCAN */
Route::get('/scan',        [ScanController::class, 'index']);
Route::get('/scan/{kode}', [ScanController::class, 'result']);
Route::post('/tagihan/bulk-delete', [TagihanController::class, 'bulkDelete'])->name('tagihan.bulkDelete');

Route::get('/layanan/{id}/detail', [DetailController::class, 'index'])->name('detail');

// Isolir & aktifkan
Route::get('/layanan/{id}/isolir',   [LayananController::class, 'isolir'])  ->name('layanan.isolir');
Route::get('/layanan/{id}/aktifkan', [LayananController::class, 'aktifkan'])->name('layanan.aktifkan');
