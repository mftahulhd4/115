<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // PERBAIKAN NAMA: Gate untuk hak akses tertinggi (hanya admin)
        Gate::define('is-admin', function (User $user) {
            return $user->role === 'admin';
        });

        // Gate untuk CRUD data Santri (admin dan pengurus)
        Gate::define('manage-santri', function (User $user) {
            return in_array($user->role, ['admin', 'pengurus']);
        });

        // Gate untuk CRUD data Perizinan (admin, pengurus, dan asatid)
        Gate::define('manage-perizinan', function (User $user) {
            return in_array($user->role, ['admin', 'pengurus', 'asatid']);
        });
        
        // Gate untuk CRUD data Tagihan (admin dan pengurus)
        Gate::define('manage-tagihan-full', function (User $user) {
            return in_array($user->role, ['admin', 'pengurus']);
        });
        
        // Gate untuk HANYA MELIHAT data Tagihan (semua role)
        Gate::define('view-tagihan', function (User $user) {
            return in_array($user->role, ['admin', 'pengurus', 'asatid']);
        });
    }
}