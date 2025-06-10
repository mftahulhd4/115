<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\PerizinanController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Santri Resource Routes
    Route::get('/santri/{santri}/print', [SantriController::class, 'printPDF'])->name('santri.print'); // <-- TAMBAHAN UNTUK PRINT PDF
    Route::resource('santri', SantriController::class);

    // Perizinan Resource Routes (dengan search santri)
    // TODO: Tambahkan route print PDF untuk Perizinan jika diperlukan
    
    Route::get('/perizinan/{perizinan}/print', [PerizinanController::class, 'printPDF'])->name('perizinan.print'); //
    Route::get('/perizinan/search-santri', [PerizinanController::class, 'searchSantri'])->name('perizinan.searchSantri');
    Route::resource('perizinan', PerizinanController::class);

    // Tagihan Resource Routes (dengan search santri)
    // TODO: Tambahkan route print PDF untuk Tagihan jika diperlukan
    Route::get('/tagihan/{tagihan}/print', [TagihanController::class, 'printPDF'])->name('tagihan.print');
    Route::get('/tagihan/search-santri', [TagihanController::class, 'searchSantri'])->name('tagihan.searchSantri');
    Route::resource('tagihan', TagihanController::class);

    // User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
});

if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}