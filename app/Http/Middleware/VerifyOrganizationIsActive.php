<?php

namespace App\Http\Middleware;

use RJ;
use Auth;
use Closure;
use App\Traits\ExcludeUris;

class VerifyOrganizationIsActive
{
    use ExcludeUris;

    /**
     * URLs where it should not be checked if oragnization is deactive
     *
     * @var array
     */
    protected $except = [
        '/logout',
        '/login',
        '/deactive',
        '/admin/*'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // If user is super admin, or unauthenticated return without doing anything
        if (! $request->user() || $request->user()->isSuperAdmin() || $request->user()->isAppAdmin()) {
            return $next($request);
        }

        // Find the most recent organization of the currently authenticated user
        $organization = $request->user()->currentOrganization();

        // If no organization found, then logout and redirect to home page with an error
        if (! $organization) {
            Auth::logout();
            return redirect()->to('login')->with('errorMessage', __('You are not assigned to any organization.'));
        }

        //If organization is deactive then show deactive page
        if ((! empty($organization->deactivated_at) || ! is_null($organization->deactivated_at)) && ! $this->inExceptArray($request)) {

            return redirect()->to('deactive');
        }

        return $next($request);
    }
}
