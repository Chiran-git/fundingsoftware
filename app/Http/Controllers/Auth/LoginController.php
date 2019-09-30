<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Find the current organization and role of the user and set it in the session
        if (! $user->isOrganization()) {
            return;
        }

        if (! ($organization = $user->currentOrganization())) {
            // Logout the user if no associated org found and show an error
            auth()->logout();
            redirect()->to('login')->with(
                'errorMessage',
                __('You are not associated with any organization.')
            );
        }

        $user->update([
            'last_login_at' => now(),
        ]);

        $user->organization = $organization;
        $user->role = $organization->pivot->role;

        if ($user->isOrganization()) {
            $this->redirectTo = '/dashboard';
        }
    }
}
