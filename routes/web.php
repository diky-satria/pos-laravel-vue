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
use App\Http\Controllers\PenjualanGagalController;

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
        Route::get('penjualan_gagal', [AdminController::class, 'penjualan_gagal']);
        
        Route::group(['prefix' => 'data'], function(){
            Route::resource('supplier', SupplierController::class);
            Route::resource('kategori', KategoriController::class);
            Route::resource('petugas', PetugasController::class);
            Route::resource('penjualan_gagal', PenjualanGagalController::class);

            Route::get('data_select', [BarangController::class, 'data_select']);
            Route::get('barang', [BarangController::class, 'index']);
            Route::get('barang/{id}', [BarangController::class, 'show']);
            Route::post('barang', [BarangController::class, 'store']);
            Route::post('barang/{id}', [BarangController::class, 'update']);
            Route::delete('barang/{id}', [BarangController::class, 'destroy']);
        });
    });

    Route::resource('pelanggan', PelangganController::class);

    //route transaksi
    Route::get('kode_tgl', [PenjualanController::class, 'kode_tgl']);
    Route::get('select_barang', [PenjualanController::class, 'select_barang']);
    Route::get('select_pelanggan', [PenjualanController::class, 'select_pelanggan']);
    Route::get('penjualan', [PenjualanController::class, 'index']);
    Route::post('tambah_transaksi', [PenjualanController::class, 'tambah_transaksi']);
    Route::get('detail_data_transaksi/{kode}', [PenjualanController::class, 'detail_data_transaksi']);
    Route::delete('hapus_data_transaksi/{id_barang}/{id_pivot}/{qty}', [PenjualanController::class, 'hapus_data_transaksi']);
    Route::patch('tambah_data_transaksi/{id_barang}/{id_pivot}', [PenjualanController::class, 'tambah_data_transaksi']);
    Route::patch('kurang_data_transaksi/{id_barang}/{id_pivot}', [PenjualanController::class, 'kurang_data_transaksi']);
    Route::post('update_status_transaksi/{kode}', [PenjualanController::class, 'update_status_transaksi']);

    Route::resource('riwayat', RiwayatController::class);

    Route::get('barang-in', [BarangInController::class, 'index']);

    Route::get('barang-out', [BarangOutController::class, 'index']);

    Route::get('laporan-harian', [LaporanController::class, 'laporan_harian']);
    Route::get('export-harian-excel', [LaporanController::class, 'exportHarianExcel']);
    Route::get('export-harian-pdf', [LaporanController::class, 'exportHarianPdf']);

    Route::get('laporan-bulanan', [LaporanController::class, 'laporan_bulanan']);
    Route::get('laporan-bulanan-data', [LaporanController::class, 'laporan_bulanan_data']);
    Route::get('export-bulanan-excel', [LaporanController::class, 'exportBulananExcel']);
    Route::get('export-bulanan-pdf', [LaporanController::class, 'exportBulananPdf']);
});

