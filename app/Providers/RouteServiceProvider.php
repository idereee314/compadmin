<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    protected $np_general = '';
    protected $np_location = 'location';
    protected $np_listing = 'listing';
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //$this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware('listing')
                ->namespace($this->namespace)
                ->group(base_path('routes/listing.php'));
        });
        
    }

    public function map()
    {
        $this->mapWebRoutes();
        $this->mapLocationRoutes();
        $this->mapListingRoutes();

        //
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    /*protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }*/

        /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    protected function mapLocationRoutes()
    {
        Route::middleware('web')
             ->prefix('location')
             ->namespace($this->np_location)
             ->group(base_path('routes/location.php'));
    }

     /**
     * 
     */
    protected function mapListingRoutes()
    {
        Route::middleware('web')
             ->prefix('listing')
             ->namespace($this->np_listing)
             ->group(base_path('routes/listing.php'));
    }
}
