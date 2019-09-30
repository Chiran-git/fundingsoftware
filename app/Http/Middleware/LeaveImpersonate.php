<?php

namespace App\Http\Middleware;

use Closure;

class LeaveImpersonate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $manager = app('impersonate');

        if ($manager->isImpersonating()) {
            $manager->leave();
        }

        return $next($request);
    }
}
