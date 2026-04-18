<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // Importation nécessaire
use App\Models\User;                 // Importation nécessaire

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
        /**
         * Définition de l'accès au module Militaire (Army)
         * Cette Gate vérifie l'email de l'utilisateur connecté.
         */
        Gate::define('access-military', function (User $user) {
            return $user->email === 'admin@omega.com';
        });
    }
}
