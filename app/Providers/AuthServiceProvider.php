<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to be used for authentication.
     */
    // protected $model = \App\Models\User::class;

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Example Gate definition
        // Gate::define('admin', function ($user) {
        //     return $user->isAdmin();
        // });
    }
}
