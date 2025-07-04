<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\PerizinanController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\PendidikanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\StatusController;

// Rute halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Grup rute yang memerlukan autentikasi (login)
Route::middleware('auth')->group(function () {
    // Rute Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Manajemen User (hanya untuk admin)
    Route::resource('users', UserController::class)->middleware('can:manage-users');
    
    // Rute Manajemen Santri
    Route::resource('santri', SantriController::class);
    Route::get('/santri/{santri}/pdf', [SantriController::class, 'detailPdf'])->name('santri.detailPdf');
    Route::get('/santri/{santri}/print', [SantriController::class, 'print'])->name('santri.print');
    
    // --- PERBAIKAN FINAL UNTUK RUTE PERIZINAN ---
    Route::get('/perizinan/search', [PerizinanController::class, 'searchSantri'])->name('perizinan.search');
    Route::resource('perizinan', PerizinanController::class);
    Route::get('/perizinan/{perizinan}/pdf', [PerizinanController::class, 'detailPdf'])->name('perizinan.detailPdf');
    Route::get('/perizinan/{perizinan}/print', [PerizinanController::class, 'print'])->name('perizinan.print');
    // --- AKHIR DARI PERBAIKAN ---

    // Rute Manajemen Tagihan
    Route::prefix('tagihan')->name('tagihan.')->group(function () {
        Route::get('/', [TagihanController::class, 'index'])->name('index');
        Route::get('/create', [TagihanController::class, 'create'])->name('create');
        Route::post('/', [TagihanController::class, 'store'])->name('store');
        Route::get('/{jenisTagihan}', [TagihanController::class, 'show'])->name('show');
        Route::get('/{jenisTagihan}/edit', [TagihanController::class, 'edit'])->name('edit');
        Route::put('/{jenisTagihan}', [TagihanController::class, 'update'])->name('update');
        Route::delete('/{jenisTagihan}', [TagihanController::class, 'destroy'])->name('destroy');
        Route::get('/{jenisTagihan}/assign', [TagihanController::class, 'assign'])->name('assign');
        Route::post('/{jenisTagihan}/assign', [TagihanController::class, 'storeAssignment'])->name('storeAssignment');
        
        // Rute untuk tagihan per santri
        Route::get('/santri/{tagihan}', [TagihanController::class, 'showSantriBill'])->name('showSantriBill');
        Route::get('/santri/{tagihan}/edit', [TagihanController::class, 'editSantriBill'])->name('editSantriBill');
        Route::put('/santri/{tagihan}', [TagihanController::class, 'updateSantriBill'])->name('updateSantriBill');
        Route::delete('/santri/{tagihan}/delete', [TagihanController::class, 'destroySantriBill'])->name('destroySantriBill');
        
        // Rute untuk cetak kuitansi
        Route::get('/santri/{tagihan}/receipt-pdf', [TagihanController::class, 'pdfReceipt'])->name('pdfReceipt');
        Route::get('/santri/{tagihan}/receipt-print', [TagihanController::class, 'printReceipt'])->name('printReceipt');
    });

    // Rute CRUD untuk Data Master
    Route::prefix('master-data')->name('master.')->group(function () {
        Route::resource('pendidikan', PendidikanController::class)->except(['show']);
        Route::resource('kelas', KelasController::class)->except(['show']);
        Route::resource('status', StatusController::class)->except(['show']);
    });
});

// Rute untuk proses autentikasi (login, logout, dll)
require __DIR__.'/auth.php';