<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerizinanController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Grup untuk Manajemen Santri
    Route::get('/santri/{santri}/print', [SantriController::class, 'print'])->name('santri.print');
    Route::get('/santri/{santri}/pdf', [SantriController::class, 'detailPdf'])->name('santri.detailPdf');
    Route::resource('santri', SantriController::class);
    
    // Grup untuk Manajemen Perizinan
    Route::post('/perizinan/{perizinan}/update-status', [PerizinanController::class, 'updateStatus'])->name('perizinan.updateStatus');
    Route::get('perizinan/search-santri', [PerizinanController::class, 'searchSantri'])->name('perizinan.search');
    Route::resource('perizinan', PerizinanController::class);
    Route::get('/perizinan/{perizinan}/pdf', [PerizinanController::class, 'detailPdf'])->name('perizinan.detailPdf');
    Route::get('/perizinan/{perizinan}/print', [PerizinanController::class, 'print'])->name('perizinan.print');

    // Grup untuk Manajemen Tagihan
    Route::prefix('tagihan')->name('tagihan.')->group(function () {
        Route::get('/', [TagihanController::class, 'index'])->name('index');
        Route::get('/create', [TagihanController::class, 'create'])->name('create');
        Route::post('/', [TagihanController::class, 'store'])->name('store');
        Route::get('/{jenisTagihan}', [TagihanController::class, 'show'])->name('show');
        Route::get('/{jenisTagihan}/edit', [TagihanController::class, 'edit'])->name('edit');
        Route::put('/{jenisTagihan}', [TagihanController::class, 'update'])->name('update');
        Route::delete('/{jenisTagihan}', [TagihanController::class, 'destroy'])->name('destroy');
        
        // Route untuk alur kerja massal
        Route::get('/{jenisTagihan}/assign', [TagihanController::class, 'assign'])->name('assign');
        Route::post('/{jenisTagihan}/assign', [TagihanController::class, 'storeAssignment'])->name('storeAssignment');
        
        // --- PERUBAHAN & PENAMBAHAN ROUTE UNTUK TAGIHAN INDIVIDUAL ---
        Route::get('/santri/{tagihan}', [TagihanController::class, 'showSantriBill'])->name('showSantriBill');
        Route::delete('/santri/{tagihan}', [TagihanController::class, 'destroySantriBill'])->name('destroySantriBill');

        // Route untuk edit & update tagihan individual
        Route::get('/santri/{tagihan}/edit', [TagihanController::class, 'editSantriBill'])->name('editSantriBill');
        Route::put('/santri/{tagihan}', [TagihanController::class, 'updateSantriBill'])->name('updateSantriBill');

        // Route untuk cetak kuitansi
        Route::get('/santri/{tagihan}/print', [TagihanController::class, 'printReceipt'])->name('printReceipt');
        Route::get('/santri/{tagihan}/pdf', [TagihanController::class, 'pdfReceipt'])->name('pdfReceipt');
    }); // <-- Penutup untuk grup 'tagihan' seharusnya di sini

    // Grup untuk Manajemen User (dengan middleware 'can')
    Route::middleware('can:manage-users')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Grup untuk Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';