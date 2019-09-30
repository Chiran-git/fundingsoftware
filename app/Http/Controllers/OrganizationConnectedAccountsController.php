<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\OrganizationConnectedAccount;
use App\Repositories\Contracts\OrganizationConnectedAccountRepositoryInterface;

class OrganizationConnectedAccountsController extends Controller
{
    /**
     * Account repositor
     *
     * @var OrganizationConnectedAccountRepositoryInterface
     */
    protected $repo;

    /**
     * Constructor
     *
     * @param OrganizationConnectedAccountRepositoryInterface $repo
     */
    public function __construct(OrganizationConnectedAccountRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Action to list all payout accounts
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('create', [OrganizationConnectedAccount::class, $request->user()->currentOrganization()]);

        return view('connected-accounts.index');
    }

    /**
     * Action to initiate the creation of new connected account
     * Here we redirect the user to Stripe and the user will return to "store"
     * method where we make the db entries
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $this->authorize('create', [OrganizationConnectedAccount::class, $request->user()->currentOrganization()]);

        // Prepare the Stripe express Connect account link and redirect there
        $redirectUri = route('connected-account.store');

        // Set the return url where user should be sent after an account has been created
        if ($request->return) {
            session(['stripe_return_url' => $request->return]);
        }

        // Put a unique state in the session to be used to verify the request when the user comes back from stripe
        session(['stripe_state' => (string) Str::uuid()]);

        $stripeAuthorizeUrl = \Stripe\OAuth::authorizeUrl(
            [
                'scope' => 'read_write',
                'redirect_uri' => $redirectUri,
                'state' => session('stripe_state'),
                'suggested_capabilities' => ['card_payments', 'platform_payments']

            ]/*
            ,[
                'connect_base' => \Stripe\Stripe::$connectBase . '/express',
            ]*/
        );

        return redirect($stripeAuthorizeUrl);
    }

    /**
     * Action to store the new Stripe Connected account
     * This is where the user is redirected back from stripe
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', [OrganizationConnectedAccount::class, $request->user()->currentOrganization()]);

        // The state in the request should match what we have in the session
        if ($request->state !== (string) session('stripe_state')) {
            abort(403, __('You are not authorized to perform this action.'));
        }

        if (! empty($request->code)) {
            return $this->processStore($request);
        } elseif (! empty($request->error)) {
            // The user was redirect back from the OAuth form with an error.
            return redirect(session('stripe_return_url'))->with('errorMessage', $request->error_description);
        }

        // Something fishy, serve bad request error
        abort(400, __('Bad Request'));
    }

    /**
     * Method to process the store
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function processStore($request)
    {
        // The user was redirected back from the OAuth form with an authorization code.
        try {
            $response = \Stripe\OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $request->code,
                'assert_capabilities' => ['card_payments', 'platform_payments']
            ]);
        } catch (\Stripe\Error\OAuth\OAuthBase $e) {
            abort(400, $e->getMessage());
        }

        $accountExist = true;
        //check stripe account, if not present then create new record
        if (! $account = $this->repo->findWhere([
            'organization_id' => $request->user()->currentOrganization()->id,
            'stripe_user_id' => $response->stripe_user_id
            ])) {
            $accountExist = false;
            $account = $this->repo->store([
                'organization_id' => $request->user()->currentOrganization()->id,
                'created_by_id' => $request->user()->id,
                'is_default' => false,
                'nickname' => 'New Bank Account',
                'stripe_user_id' => $response->stripe_user_id,
                'stripe_access_token' => $response->access_token,
                'stripe_livemode' => $response->livemode,
                'stripe_refresh_token' => $response->refresh_token,
                'stripe_token_type' => $response->token_type,
                'stripe_publishable_key' => $response->stripe_publishable_key,
                'stripe_scope' => $response->scope,
            ]);
        }

        if ($account) {
            // TODO: For standard connect accounts we can't seem to get external bank info
            // So comment it out for now. It probably will work on live so will have to test.
            //$this->repo->updateBankInfo($account->id);
        } else {
            abort(400, __('Unable to add Bank Account.'));
        }

        if (session('stripe_return_url')) {
            $redirect = session('stripe_return_url');
            if (strpos($redirect, '?') === false) {
                $redirect .= '?';
            } else {
                $redirect .= '&';
            }
            // Append the newly created account id to the url
            $redirect .= 'connected_account_id=' . $account->id;

            if ($accountExist === true) {
                $redirect .= '&account_exist=' . $accountExist;
            }

            return redirect($redirect);
        }

        return redirect()->route('connected-account.index')->with('successMessage', __('Bank Account created successfully'));
    }
}
