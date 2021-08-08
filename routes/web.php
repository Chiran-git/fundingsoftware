<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest'])->get('/', function () {
    return view('home');
})->name('home');

Route::get('/signup', function () {
    return view('auth.org-signup');
})->middleware(['guest'])
->name('org-signup');

Auth::routes(['register' => false, 'verify' => true]);

// Override login route to apply middleware
Route::middleware(['revalidate', 'guest'])->get('login', function () {
    return view('auth.login');
})->name('login');

Route::get('terms-of-service', function () {
    return view('pages.terms-of-service');
})->name('terms-of-service');

Route::get('privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('deactive', function () {
    return view('organization.deactive');
})->name('organization.deactive');

Route::get('account-user', function () {
    return view('organization.partials.account-user');
})->name('account-user');

Route::get('accounts', function () {
    return view('donations.accounts');
})->name('accounts');

// Only guest (non logged in user) can accept invitation
Route::middleware(['guest'])
    ->get('/organization/{organization}/invitation/{code}/accept', 'InvitationsController@accept')
    ->name('accept-invitation');

Route::post('/organization/search', 'OrganizationsController@search')
    ->name('organization.search');

/**
 * All authenticated routes
 */
Route::middleware(['auth', 'revalidate'])->group(function () {
    Route::get('myaccount', 'DashboardController@getMyProfile')->name('myaccount');

    Route::get('change-password', 'DashboardController@getChangePassword')->name('change-password');

    Route::get('dashboard', 'DashboardController@show')->name('dashboard');

    Route::get('superadmin', 'DashboardController@superAdminDashBoard')->name('superadmin');

    Route::get('setup/account', 'OrganizationsController@accountSetup')
        ->name('organization.setup-account');

    Route::get('organization/edit', 'OrganizationsController@edit')
        ->name('organization.edit');

    Route::get('organization/create', 'OrganizationsController@create')
        ->name('organization.create');

    Route::get('campaign', 'CampaignsController@index')->name('campaign.index');

    Route::get('campaign/create', 'CampaignsController@create')
    ->name('campaign.create');

    Route::get('connected-account/create', 'OrganizationConnectedAccountsController@create')
        ->name('connected-account.create');

    Route::get('connected-account/store', 'OrganizationConnectedAccountsController@store')
        ->name('connected-account.store');

    Route::get('connected-account', 'OrganizationConnectedAccountsController@index')
        ->name('connected-account.index');

    Route::get('campaign/{campaign}/edit', 'CampaignsController@edit')
        ->name('campaign.edit');

    Route::get('campaign/{campaign}/details', 'CampaignsController@details')
        ->name('campaign.admin-details');

    Route::get('donors', 'DonorsController@index')->name('donors.index');

    Route::get('/organization/{organization}/donor/{donor}', 'DonorsController@show')
        ->name('donor.show');

    Route::get('/organization/{organization}/donors/export', 'DonorsController@export')->name('donors.export');

    Route::get('/organization/{organization}/donation/create', 'DonationsController@create')
        ->name('donation.create');

    Route::get('donations', 'DonationsController@index')->name('donations.index');

    Route::get('/organization/{organization}/donations/export', 'DonationsController@export')->name('donations.export');

    Route::get('payouts', 'PayoutsController@index')->name('payouts.index');

    Route::get('report-affiliations',
        'OrganizationReportsController@affiliationReports')
        ->name('reports.affiliations');
});

Route::middleware(['auth', 'revalidate', 'impersonate.leave'])
    ->prefix('admin')->group(function () {
        Route::get('/dashboard', 'Admin\DashboardController@show')
            ->name('admin.dashboard');

        Route::get('/organizations', 'Admin\OrganizationsController@index')->name('admin.organizations');

        Route::get('/campaigns', 'Admin\CampaignsController@index')->name('admin.campaigns');

        Route::get('/campaign/{campaign}/details', 'Admin\CampaignsController@details')
        ->name('campaign.admin-details');

        Route::get('/admins', 'Admin\AdminsController@index')->name('admin.admins');

        Route::get('/create', 'Admin\AdminsController@create')->name('admin.create');

        Route::get('/organization/create', 'Admin\OrganizationsController@create')->name('admin.organization.create');

        Route::get('/organization/{organization}/campaign/create', 'Admin\CampaignsController@create')->name('admin.campaign.create');

        Route::get('/organization/{organization}/donation/create', 'Admin\DonationsController@create')->name('admin.donation.create');

        Route::post('/search', 'Admin\DashboardController@search')
            ->name('admin.search');

        Route::get('/{user}/edit', 'Admin\AdminsController@edit')->name('admin.edit');

        Route::get('/payout', 'Admin\PayoutsController@create')->name('admin.payout');

        Route::get('myaccount', 'Admin\DashboardController@getMyAccount')->name('admin.myaccount');

        Route::get('change-password', 'Admin\DashboardController@getChangePassword')->name('admin.change-password');

        Route::get ('/impersonate/{organization}', 'DashboardController@impersonate')->name('impersonate');

        Route::get ('/reports/online-donations', 'Admin\DashboardController@onlineDonations')->name('reports.online-donations');

        Route::get ('/reports/donation-stats', 'Admin\DashboardController@reportDonationStats')->name('reports.donation-stats');

        Route::get ('/reports/category-stats', 'Admin\DashboardController@reportCategoryStats')->name('reports.category-stats');

        Route::get ('/reports/affiliation-donations', 'Admin\DashboardController@reportAffiliationDonations')->name('reports.affiliation-donations');
});

### Donor Routes ####

Route::get('/{orgSlug}/{campSlug}/donate', 'CampaignsController@donate')
    ->name('campaign.donate');

Route::get('/{orgSlug}/{campSlug}/donation', 'DonationsController@createForDonor')
    ->name('donation.make');

Route::post('/donation/{campSlug}', 'DonationsController@store')
    ->name('donation.store');

Route::get('/{orgSlug}/{campSlug}/donation/{donation}/success', 'DonationsController@success')
    ->name('donation.success');

Route::get('public-donate-email', function () {
    return view('donor.public-donate-email');
})->name('public-donate-email');

Route::get('public-campaign-deactivated', function () {
    return view('donor.public-campaign-deactivated');
})->name('public-campaign-deactivated');

Route::get('campaign/edit-campaign', function () {
    return view('campaign.edit-campaign');
})->name('campaign/edit-campaign');

Route::get('/{orgSlug}', 'OrganizationsController@show')->name('organization.show');

Route::get('/{orgSlug}/{campSlug}', 'CampaignsController@show')->name('campaign.show');
