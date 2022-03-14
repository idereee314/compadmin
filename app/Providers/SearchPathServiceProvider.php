<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SearchPathServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $searchPath = config('database.connections.pgsql.search_path');

        \DB::statement("SET search_path TO $searchPath");
    }
}
