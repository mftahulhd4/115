<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log; // Anda bisa membiarkan ini atau menghapusnya jika tidak ada log lain

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Log::info('[AppServiceProvider] Metode boot() DIPANGGIL.'); // Log ini boleh tetap ada untuk memastikan AppServiceProvider berjalan

        // PASTIKAN TIDAK ADA KODE DI BAWAH INI YANG MENCOBA MEMANGGIL AuthServiceProvider->boot() SECARA MANUAL
        /*
        try {
            $authServiceProvider = $this->app->make(\App\Providers\AuthServiceProvider::class);
            if (method_exists($authServiceProvider, 'boot')) {
                $this->app->call([$authServiceProvider, 'boot']);
                Log::info('[AppServiceProvider] Memanggil AuthServiceProvider->boot() secara manual BERHASIL.');
            } else {
                Log::error('[AppServiceProvider] Metode boot() tidak ditemukan di AuthServiceProvider.');
            }
        } catch (\Throwable $e) {
            Log::error('[AppServiceProvider] Gagal memanggil AuthServiceProvider->boot() secara manual: ' . $e->getMessage());
        }
        */
    }
}