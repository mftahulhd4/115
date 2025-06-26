<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\PerizinanController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Route Santri ---
    Route::get('santri/cetak-pdf/{santri}', [SantriController::class, 'cetakDetailPdf'])->name('santri.cetakDetailPdf');
    Route::get('santri/cetak-browser/{santri}', [SantriController::class, 'cetakBrowser'])->name('santri.cetakBrowser');
    Route::resource('santri', SantriController::class);
    // --- Route Perizinan ---
    Route::resource('perizinan', PerizinanController::class);
    Route::get('/search-santri', [PerizinanController::class, 'searchSantri'])->name('perizinan.searchSantri');
    Route::get('/perizinan/cetak-pdf', [PerizinanController::class, 'cetakPdf'])->name('perizinan.pdf');
    Route::get('/perizinan/{perizinan}/print', [PerizinanController::class, 'cetakBrowser'])->name('perizinan.cetakBrowser');
    Route::get('/perizinan/{perizinan}/pdf-detail', [PerizinanController::class, 'cetakDetailPdf'])->name('perizinan.cetakDetailPdf');
    
    // --- Route Tagihan ---
    Route::get('/tagihan/search-santri', [TagihanController::class, 'searchSantri'])->name('tagihan.searchSantri');
    Route::get('/tagihan/cetak-pdf', [TagihanController::class, 'cetakPdf'])->name('tagihan.cetakPdf');
    Route::get('/tagihan/{tagihan}/print', [TagihanController::class, 'cetakBrowser'])->name('tagihan.cetakBrowser');
    Route::get('/tagihan/{tagihan}/pdf-detail', [TagihanController::class, 'cetakDetailPdf'])->name('tagihan.cetakDetailPdf');
    Route::resource('tagihan', TagihanController::class);
    // TAMBAHKAN DUA RUTE DI BAWAH INI
    Route::get('/tagihan/{tagihan}/print', [TagihanController::class, 'cetakBrowser'])->name('tagihan.cetakBrowser');
    Route::get('/tagihan/{tagihan}/pdf-detail', [TagihanController::class, 'cetakDetailPdf'])->name('tagihan.cetakDetailPdf');
    
    // --- Route User & Profile ---
    Route::resource('users', UserController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';