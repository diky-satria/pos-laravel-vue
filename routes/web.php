<?php

use App\Models\Supplier;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\BarangInController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangOutController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenjualanController;

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
    return view('auth.login');
})->middleware(['logged']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('role:admin')->name('home');

Route::group(['middleware' => 'auth'], function(){
    Route::group(['middleware' => ['role:admin']], function(){
        Route::get('dashboard', [AdminController::class, 'dashboard']);
        Route::get('supplier', [AdminController::class, 'supplier']);
        Route::get('kategori', [AdminController::class, 'kategori']);
        Route::get('barangs', [AdminController::class, 'barangs']);
        Route::get('petugas', [PetugasController::class, 'index']);

        Route::group(['prefix' => 'data'], function(){
            Route::resource('supplier', SupplierController::class);
            Route::resource('kategori', KategoriController::class);

            Route::get('data_select', [BarangController::class, 'data_select']);
            Route::get('barang', [BarangController::class, 'index']);
            Route::get('barang/{id}', [BarangController::class, 'show']);
            Route::post('barang', [BarangController::class, 'store']);
            Route::post('barang/{id}', [BarangController::class, 'update']);
            Route::delete('barang/{id}', [BarangController::class, 'destroy']);
        });
    });

    Route::resource('pelanggan', PelangganController::class);

    Route::get('penjualan', [PenjualanController::class, 'index']);

    Route::get('riwayat', [RiwayatController::class, 'index']);

    Route::get('barang-in', [BarangInController::class, 'index']);

    Route::get('barang-out', [BarangOutController::class, 'index']);

    Route::get('laporan-harian', [LaporanController::class, 'laporan_harian']);
    Route::get('laporan-bulanan', [LaporanController::class, 'laporan_bulanan']);
});

