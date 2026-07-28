<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\KondisiController;
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
});

require __DIR__.'/auth.php';
