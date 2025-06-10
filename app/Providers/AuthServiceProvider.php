<?php

namespace App\Providers;

// Pastikan semua use statement ini ada dan benar
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User; // Pastikan path ke User model Anda benar
use Illuminate\Support\Facades\Log; // Pastikan Log di-import

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Biasanya ada mapping policy di sini jika Anda menggunakan policy
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
{
    // Log::info('============================================================');
    // Log::info('[AuthServiceProvider] METODE BOOT() AuthServiceProvider DIPANGGIL.');
    // Log::info('============================================================');

    $this->registerPolicies();

    Gate::define('manage-users', function (User $user) {
        $isAdmin = $user->isAdmin();
        // Log::info('[AuthServiceProvider] Gate "manage-users" dievaluasi untuk user: ' . $user->email . '. Peran: ' . $user->role . '. Method isAdmin() mengembalikan: ' . ($isAdmin ? 'Ya' : 'Tidak'));
        return $isAdmin;
    });
}
}