<?php

namespace App\Http\Middleware;

use Closure;

class ApiVersion
{
    /**
     * Handle an incoming request.
     *
     * Read https://medium.com/@juampi92/api-versioning-using-laravels-resources-b1687a6d2c22
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param  string                  $version API version
     * @return mixed
     */
    public function handle($request, Closure $next, $version)
    {
        // Set the API version for the current request
        config(['app.api_version' => $version]);

        return $next($request);
    }
}
