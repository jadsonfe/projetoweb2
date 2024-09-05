<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Book;
use App\Policies\BookPolicy;

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
    public function boot()
{
    // Registrar o middleware `checkRole`
    \Illuminate\Support\Facades\Gate::define('checkRole', function ($user, $role) {
        return $user->role === $role;
    });
}

    /**
     * Register the policies for the application.
     */
    protected function registerPolicies(): void
    {
        // Registra as policies aqui
        Gate::policy(Book::class, BookPolicy::class);
    }
}
