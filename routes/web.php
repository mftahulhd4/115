<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\PerizinanController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ... (Route lain tidak perlu diubah)
    Route::resource('santri', SantriController::class);
    Route::resource('perizinan', PerizinanController::class);
    Route::middleware('can:manage-users')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =============================================================
    // == GRUP ROUTE TAGIHAN VERSI FINAL & STABIL ==
    // =============================================================
    Route::prefix('tagihan')->name('tagihan.')->group(function () {
        // Route untuk Jenis Tagihan (Master)
        Route::get('/', [TagihanController::class, 'index'])->name('index');
        Route::get('/create', [TagihanController::class, 'create'])->name('create');
        Route::post('/', [TagihanController::class, 'store'])->name('store');
        Route::get('/{jenisTagihan}', [TagihanController::class, 'show'])->name('show');
        Route::get('/{jenisTagihan}/edit', [TagihanController::class, 'edit'])->name('edit');
        Route::put('/{jenisTagihan}', [TagihanController::class, 'update'])->name('update');
        Route::delete('/{jenisTagihan}', [TagihanController::class, 'destroy'])->name('destroy');
        
        Route::get('/{jenisTagihan}/assign', [TagihanController::class, 'assign'])->name('assign');
        Route::post('/{jenisTagihan}/assign', [TagihanController::class, 'storeAssignment'])->name('store_assignment');
        
        // Route untuk Tagihan Spesifik per Santri
        Route::get('/santri/{tagihan}', [TagihanController::class, 'showSantriBill'])->name('show_santri_bill');
        Route::delete('/santri/{tagihan}', [TagihanController::class, 'destroySantriBill'])->name('destroy_santri_bill');
        Route::get('/santri/{tagihan}/edit', [TagihanController::class, 'editSantriBill'])->name('edit_santri_bill');
        Route::put('/santri/{tagihan}', [TagihanController::class, 'updateSantriBill'])->name('update_santri_bill');
        Route::get('/santri/{tagihan}/print', [TagihanController::class, 'printReceipt'])->name('print_receipt');
        Route::get('/santri/{tagihan}/pdf', [TagihanController::class, 'pdfReceipt'])->name('pdf_receipt');
    });
});

require __DIR__.'/auth.php';