<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SerahTerimaController;
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
});

require __DIR__.'/auth.php';
