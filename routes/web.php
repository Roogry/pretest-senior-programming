<?php

use App\Http\Controllers\PenjualanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('penjualan', PenjualanController::class);
Route::post('penjualan/{penjualan}', [PenjualanController::class, 'storeDetailPenjualan'])->name('penjualan.detail.store');
Route::delete('penjualan/{penjualan}/{id_barang}', [PenjualanController::class, 'destroyDetailPenjualan'])->name('penjualan.detail.destroy');
