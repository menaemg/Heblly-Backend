<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Auth\AdminUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\EloquentUserProvider;
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
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Auth::provider('admin', function ($app, array $config) {
            return new EloquentUserProvider($app['hash'], $config['model']);
        });

        Auth::extend('admin', function ($app, $name, array $config) {
            return new AdminUserProvider($app['hash'], $config['model']);
        });
    }
}
