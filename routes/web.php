<?php

use App\Http\Controllers\CabangController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KategoriHargaJualController;
use App\Http\Controllers\MerekController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PusatController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\UkuranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\KasBonController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PromosiController;
use App\Http\Controllers\MetodePembayaranController;
use App\Http\Controllers\PembayaranKasBonController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenggajianController;
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

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    //ACL -- Access Control List
    Route::resource('user', UserController::class);
    Route::resource('role', RoleController::class);
    Route::resource('task', TaskController::class);

    Route::get('/ubahuser', [UserController::class, 'ubah'])->name('user.ubah');
    Route::put('/simpanuser', [UserController::class, 'simpan'])->name('user.simpan');

    // Data Dasar
    Route::resource('satuan', SatuanController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('merek', MerekController::class);
    Route::resource('jenis', JenisController::class);
    Route::resource('ukuran', UkuranController::class);
    Route::resource('produk', ProdukController::class);
    Route::resource('kategori_harga_jual', KategoriHargaJualController::class);
    Route::resource('pusat', PusatController::class);
    Route::resource('cabang', CabangController::class);
    Route::resource('gudang', GudangController::class);
    Route::resource('toko', TokoController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('promosi', PromosiController::class);
    Route::resource('metode-pembayaran', MetodePembayaranController::class);

    //kasbon
    Route::resource('kas-bon', KasBonController::class);
    Route::get('kasbon/{id}', [KasBonController::class, 'detail'])->name('kas-bon.detail');;
    Route::resource('pembayaran-kas-bon', PembayaranKasBonController::class);

    //metode pembayaran
    Route::resource('bank', BankController::class);
    Route::get('metode-bank/{id}', [BankController::class, 'metode'])->name('bank.metode');

    //pelanggan
    Route::resource('pelanggan', PelangganController::class);
    Route::post('/simpanpelanggan', [PelangganController::class, 'pelanggan'])->name('pelanggan.simpan');

    // Penjualan
    Route::resource('penjualan', PenjualanController::class);

    // Pembelian
    Route::resource('pembelian', PembelianController::class);

    // Shif
    Route::resource('shift', ShiftController::class);

    // Roster
    Route::resource('roster', RosterController::class);
    Route::get('roster-import', [RosterController::class, 'import'])->name('roster.import');
    Route::post('roster-import', [RosterController::class, 'import_process'])->name('roster.import_process');
    Route::post('roster-proses', [RosterController::class, 'process'])->name('roster.proses');

    Route::get('roster-download', [RosterController::class, 'download'])->name('roster.download');

    // Jadwal
    Route::resource('jadwal', JadwalController::class);

    // Absensi
    Route::resource('absensi', AbsensiController::class);
    Route::get('absensi-pilih', [AbsensiController::class, 'absen'])->name('absensi.absen');
    Route::get('absensi-scan', [AbsensiController::class, 'scan'])->name('absensi.scan');
    Route::get('absensi-success', function () {
        return view('absensi.success', ['title' => 'Success']);
    })->name('absensi.success');
    Route::get('absensi-error', function () {
        return view('absensi.error', ['title' => 'Error']);
    })->name('absensi.error');

    // Laporan Penjualan
    Route::get('laporan-penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('laporan-penjualan/{slug}', [LaporanController::class, 'detailPenjualan'])->name('laporan.penjualan-detail');
    // Laporan Pembelian
    Route::get('laporan-pembelian', [LaporanController::class, 'pembelian'])->name('laporan.pembelian');
    Route::get('laporan-pembelian/{slug}', [LaporanController::class, 'detailPembelian'])->name('laporan.pembelian-detail');

    // Pengajian
    Route::resource('penggajian', PenggajianController::class)->only(['index', 'show']);
    Route::get('penggajian-detail', [PenggajianController::class, 'detail'])->name('penggajian.detail');
});
