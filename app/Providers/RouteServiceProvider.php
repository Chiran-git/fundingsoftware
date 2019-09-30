<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        $this->routeBindings();
    }

    /**
     * Method to define some custom explicit route bindings
     *
     * @return void
     */
    protected function routeBindings()
    {
        // Explicit route model binding for /orgSlug (show organization) URLs
        Route::bind('orgSlug', function ($value) {
            return \App\Organization::active()
                ->where('slug', $value)->first() ?? abort(404);
        });

        // Explicit route model binding for /orgSlug/campSlug (show campaign) URLs
        Route::bind('campSlug', function ($value) {
            //show only published campaigns to donors and all the campaigns to organization user
            if (request()->user() && (request()->user()->isOrganization() || request()->user()->isSuperAdmin() || request()->user()->isAppAdmin())) {
                return \App\Campaign::where('slug', $value)->first() ?? abort(404);
            }
            return \App\Campaign::published()
                    ->where('slug', $value)->first() ?? abort(404);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

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

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware(['api', 'api.version:1'])
             ->namespace($this->namespace . '\Api')
             ->group(base_path('routes/api.php'));

        Route::prefix('api/v1')
             ->middleware(['api', 'api.version:1'])
             ->namespace($this->namespace . '\Api')
             ->group(base_path('routes/api.php'));
    }
}
