<?php

namespace App\Http\Middleware;

use RJ;
use Auth;
use Closure;
use App\Traits\ExcludeUris;

class SetOrganization
{
    use ExcludeUris;

    /**
     * URLs where it should not be checked if account setup is needed
     *
     * @var array
     */
    protected $except = [
        'setup/account',
        'logout',
        'email/resend',
        'email/verify',
        'email/verify/*',
        'admin/*',
        'deactive',
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

        // If organization needs setup then redirect to setup wizard
        if ($organization->present()->status() === 'setup_needed'
            && ! $this->inExceptArray($request)
        ) {
            return redirect()->route('organization.setup-account', ['modal' => 1]);
        }

        // Set the organization and role for the user
        $request->user()->organization = $organization;
        $request->user()->role = $organization->pivot->role;

        // Set the org id and role for frontend (JS)
        RJ::setScriptVariable('organizationId', $organization->id);
        RJ::setScriptVariable('role', $request->user()->role);

        return $next($request);
    }
}
