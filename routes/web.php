<?php

use App\Http\Controllers\SesiController;
use App\Http\Controllers\AdminController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::get('/', [SesiController::class,'index'])->name('login');
    Route::post('/', [SesiController::class,'login']);
});
Route::get('/home', function () {
    return redirect('admin');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class,'index']);
    Route::get('/admin/noc', [AdminController::class,'noc'])->middleware('userakses:noc');
    Route::get('/admin/cs', [AdminController::class,'cs'])->middleware('userakses:cs');
    Route::get('/admin/admin', [AdminController::class,'admin'])->middleware('userakses:admin');
    Route::post('/logout', [SesiController::class,'logout'])->name('logout');
});
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [SesiController::class, 'index'])->name('login');
});
Route::post('/login', [SesiController::class, 'login']);