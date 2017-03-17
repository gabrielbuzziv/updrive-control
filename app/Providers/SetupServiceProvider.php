<?php

namespace App\Providers;

use App\Account;
use Illuminate\Support\ServiceProvider;
use Orchestra\Support\Facades\Tenanti;

class SetupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Tenanti::connection('account', function (Account $entity, array $config) {
            $config['database'] = $entity->slug;

            return $config;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
