<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use core\CompadUserRepositoryInterface;
use core\CompadUserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CompadUserRepositoryInterface::class, CompadUserRepository::class);        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
