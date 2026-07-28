<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\KartuInventarisController;
use App\Http\Controllers\KondisiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SerahTerimaController;
use App\Http\Controllers\StokController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('barang', BarangController::class);

    Route::prefix('serah-terima')->name('serah-terima.')->group(function () {
        Route::get('/', [SerahTerimaController::class, 'index'])->name('index');
        Route::get('/create', [SerahTerimaController::class, 'create'])->name('create');
        Route::post('/', [SerahTerimaController::class, 'store'])->name('store');
        Route::get('/{serahTerima}', [SerahTerimaController::class, 'show'])->name('show');
        Route::post('/{serahTerima}/acknowledge', [SerahTerimaController::class, 'acknowledge'])->name('acknowledge');
        Route::get('/{serahTerima}/pdf', [SerahTerimaController::class, 'pdf'])->name('pdf');
    });

    Route::get('/rekap-3-pihak', [RekapController::class, 'index'])->name('rekap.3-pihak');

    Route::prefix('kondisi')->name('kondisi.')->group(function () {
        Route::get('/{barang}/create', [KondisiController::class, 'create'])->name('create');
        Route::post('/{barang}', [KondisiController::class, 'store'])->name('store');
        Route::get('/{barang}/history', [KondisiController::class, 'history'])->name('history');
    });

    Route::prefix('stok')->name('stok.')->group(function () {
        Route::get('/', [StokController::class, 'index'])->name('index');
        Route::get('/{barang}', [StokController::class, 'show'])->name('show');
        Route::get('/create/mutasi', [StokController::class, 'create'])->name('create');
        Route::post('/', [StokController::class, 'store'])->name('store');
    });

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/kategori', [LaporanController::class, 'kategori'])->name('kategori');
        Route::get('/kondisi', [LaporanController::class, 'kondisi'])->name('kondisi');
        Route::get('/lokasi', [LaporanController::class, 'lokasi'])->name('lokasi');
        Route::get('/pengadaan', [LaporanController::class, 'pengadaan'])->name('pengadaan');
    });

    Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
        Route::get('/', [PengajuanController::class, 'index'])->name('index');
        Route::get('/create', [PengajuanController::class, 'create'])->name('create');
        Route::post('/', [PengajuanController::class, 'store'])->name('store');
        Route::get('/{pengajuan}', [PengajuanController::class, 'show'])->name('show');

        Route::post('/{pengajuan}/forward-to-rapbs', [PengajuanController::class, 'forwardToRapbs'])->name('forward-to-rapbs');
        Route::post('/{pengajuan}/approve', [PengajuanController::class, 'approve'])->name('approve');
        Route::post('/{pengajuan}/reject', [PengajuanController::class, 'reject'])->name('reject');
        Route::post('/{pengajuan}/mark-dibelanjakan', [PengajuanController::class, 'markDibelanjakan'])->name('mark-dibelanjakan');
        Route::post('/{pengajuan}/mark-diserahkan-waka', [PengajuanController::class, 'markDiserahkanWaka'])->name('mark-diserahkan-waka');
        Route::post('/{pengajuan}/mark-diserahkan-pengguna', [PengajuanController::class, 'markDiserahkanPengguna'])->name('mark-diserahkan-pengguna');
        Route::post('/{pengajuan}/mark-didata', [PengajuanController::class, 'markDidata'])->name('mark-didata');
    });

    Route::prefix('kartu')->name('kartu.')->group(function () {
        Route::get('/', [KartuInventarisController::class, 'index'])->name('index');
        Route::get('/{ruangan}', [KartuInventarisController::class, 'show'])->name('show');
        Route::post('/{ruangan}/kebutuhan', [KartuInventarisController::class, 'updateKebutuhan'])->name('update-kebutuhan');
        Route::get('/{ruangan}/pdf', [KartuInventarisController::class, 'pdf'])->name('pdf');
    });
});

require __DIR__.'/auth.php';
