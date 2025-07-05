<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PendidikanController;
use App\Http\Controllers\PerizinanController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DaftarTagihanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk Manajemen Santri (Sesuai file Anda)
    Route::resource('santri', SantriController::class);
    Route::get('/santri/{santri}/pdf', [SantriController::class, 'detailPdf'])->name('santri.detailPdf');
    Route::get('/santri/{santri}/print', [SantriController::class, 'print'])->name('santri.print');

    // Rute untuk Manajemen Perizinan (Sesuai file Anda)
    Route::get('/perizinan/search', [PerizinanController::class, 'searchSantri'])->name('perizinan.search');
    Route::resource('perizinan', PerizinanController::class);
    Route::get('/perizinan/{perizinan}/pdf', [PerizinanController::class, 'detailPdf'])->name('perizinan.detailPdf');
    Route::get('/perizinan/{perizinan}/print', [PerizinanController::class, 'print'])->name('perizinan.print');

    // ==========================================================
    // RUTE TAGIHAN DENGAN NAMA YANG SUDAH DIPERBAIKI
    // ==========================================================
    Route::prefix('tagihan')->name('tagihan.')->group(function () {
        Route::get('/', [DaftarTagihanController::class, 'index'])->name('index');
        Route::get('/create', [DaftarTagihanController::class, 'create'])->name('create');
        Route::post('/', [DaftarTagihanController::class, 'store'])->name('store');
        Route::get('/{jenisTagihan}', [DaftarTagihanController::class, 'show'])->name('show');
        Route::get('/{jenisTagihan}/edit', [DaftarTagihanController::class, 'edit'])->name('edit');
        Route::put('/{jenisTagihan}', [DaftarTagihanController::class, 'update'])->name('update');
        Route::delete('/{jenisTagihan}', [DaftarTagihanController::class, 'destroy'])->name('destroy');
        Route::get('/{jenisTagihan}/assign', [DaftarTagihanController::class, 'assign'])->name('assign');
        Route::post('/{jenisTagihan}/assign', [DaftarTagihanController::class, 'storeAssignment'])->name('store_assignment'); // Disesuaikan dengan error sebelumnya
        
        // Route untuk aksi per-santri
        Route::get('/santri-bill/{tagihan}', [DaftarTagihanController::class, 'showSantriBill'])->name('showSantriBill');
        Route::get('/santri-bill/{tagihan}/edit', [DaftarTagihanController::class, 'editSantriBill'])->name('editSantriBill');
        Route::put('/santri-bill/{tagihan}', [DaftarTagihanController::class, 'updateSantriBill'])->name('updateSantriBill');

        // --- INI PERBAIKANNYA ---
        // Nama route diubah dari 'destroy_santri_bill' menjadi 'destroySantriBill'
        Route::delete('/santri-bill/{tagihan}', [DaftarTagihanController::class, 'destroySantriBill'])->name('destroySantriBill');
        // --- AKHIR PERBAIKAN ---
        
        // Route untuk kuitansi & pembatalan
        Route::post('/cancel-payment/{tagihan}', [DaftarTagihanController::class, 'cancelPayment'])->name('cancelPayment');
        Route::get('/receipt/{tagihan}/pdf', [DaftarTagihanController::class, 'pdfReceipt'])->name('pdfReceipt');
        Route::get('/receipt/{tagihan}/print', [DaftarTagihanController::class, 'printReceipt'])->name('printReceipt');
    });

    // Rute untuk Data Master (Sesuai file Anda)
    Route::prefix('master-data')->name('master.')->group(function () {
        Route::resource('pendidikan', PendidikanController::class)->except(['show']);
        Route::resource('kelas', KelasController::class)->except(['show']);
        Route::resource('status', StatusController::class)->except(['show']);
    });

    // Rute untuk Manajemen User (Sesuai file Anda)
    Route::middleware('can:manage-users')->group(function () {
        Route::resource('users', UserController::class);
    });
});

require __DIR__ . '/auth.php';