<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

##### Unauthenticated APIs ###############
Route::post('/organization', 'Organization\OrganizationsController@store')
    ->name('api.organization.store');

Route::get('/states/{countryCode}', 'StatesController@index')
    ->name('api.states.index');

Route::get('/currencies', 'CurrenciesController@index')
    ->name('api.currencies.index');

Route::get('/organization/{organization}/invitation/{code}', 'Organization\InvitationsController@show')
    ->name('api.organization.show-invite');

Route::post('/organization/{organization}/invitation/{code}', 'Organization\InvitationsController@update')
    ->name('api.organization.accept');

##### End Unauthenticated APIs ###############

Route::middleware('auth:api')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Post used as it will be multipart form
    Route::post('/user/{user}', 'UsersController@update')
        ->name('api.user.update');

    Route::put('/user/{user}/change-password', 'UsersController@changePassword')
        ->name('api.user.change-password');

    Route::get('/organization/{organization}', 'Organization\OrganizationsController@show')
        ->name('api.organization.show');

    Route::delete('/organization/{organization}', 'Organization\OrganizationsController@destroy')
        ->name('api.organization.delete');

    Route::put('/organization/{organization}/deactivate', 'Organization\OrganizationsController@deactivate')
        ->name('api.organization.deactivate');

    Route::put('/organization/{organization}/profile', 'Organization\ProfileController@update')
        ->name('api.organization.profile');

    Route::post('/organization/{organization}/design', 'Organization\DesignController@update')
        ->name('api.organization.design');

    Route::put('/organization/{organization}/user/{user}/update-user', 'Organization\UserController@update')
        ->name('api.organization.update-user');

    Route::put('/organization/{organization}/system-donor-questions', 'Organization\SystemDonorQuestionsController@update')
        ->name('api.organization.system-donor-questions');

    Route::get('/organization/{organization}/donor-question', 'DonorQuestion\DonorQuestionsController@index')
        ->name('api.donor-question.index');

    Route::post('/organization/{organization}/donor-question', 'DonorQuestion\DonorQuestionsController@store')
        ->name('api.donor-question.store');

    Route::delete('/organization/{organization}/donor-question/{donorQuestion}', 'DonorQuestion\DonorQuestionsController@destroy')
        ->name('api.donor-question.delete');

    Route::put('/organization/{organization}/donor-question/{donorQuestion}', 'DonorQuestion\DonorQuestionsController@update')
        ->name('api.donor-question.update');

    Route::put('/organization/{organization}/setup-complete', 'Organization\OrganizationsController@setupComplete')
        ->name('api.organization.setup-complete');

    Route::post('/organization/{organization}/campaign', 'Campaign\CampaignsController@store')
        ->name('api.campaign.store');

    Route::get('/organization/{organization}/campaign/{campaign}', 'Campaign\CampaignsController@show')
        ->name('api.campaign.show');

    Route::post('/organization/{organization}/campaign/{campaign}', 'Campaign\CampaignsController@update')
        ->name('api.campaign.update');

    Route::post('/organization/{organization}/campaign/{campaign}/reward', 'Campaign\RewardsController@store')
        ->name('api.campaign-reward.store');

    Route::get('/organization/{organization}/campaign/{campaign}/reward', 'Campaign\RewardsController@index')
        ->name('api.campaign-reward.index');

    Route::get('/organization/{organization}/campaign/{campaign}/reward/{reward}', 'Campaign\RewardsController@show')
        ->name('api.campaign-reward.show');

    // Using post as it has file upload with multipart/form-data
    Route::post('/organization/{organization}/campaign/{campaign}/reward/{reward}', 'Campaign\RewardsController@update')
        ->name('api.campaign-reward.update');

    Route::delete('/organization/{organization}/campaign/{campaign}/reward/{reward}', 'Campaign\RewardsController@destroy')
        ->name('api.campaign-reward.delete');

    Route::put('/organization/{organization}/campaign/{campaign}/donor-message', 'Campaign\UpdateDonorMessageController@update')
        ->name('api.campaign.donor-message');

    Route::get('/organization/{organization}/get-admins', 'Organization\AdminsController@index')
        ->name('api.organization.admins');

    Route::get('/organization/{organization}/campaign/{campaign}/campaign-admins', 'Campaign\AdminsController@index')
        ->name('api.organization.campaign-admins');

    Route::put('/organization/{organization}/campaign/{campaign}/deactivate', 'Campaign\CampaignsController@deactivate')
        ->name('api.Campaign.deactivate');

    Route::put('/organization/{organization}/campaign/{campaign}/reactivate', 'Campaign\CampaignsController@reactivate')
        ->name('api.Campaign.reactivate');

    Route::get('/organization/{organization}/account-users', 'Organization\AdminsController@accountUsers')
        ->name('api.organization.account-users');

    Route::get('/organization/{organization}/get-payouts', 'Campaign\PayoutsController@index')
        ->name('api.campaign-payouts.index');

    Route::put('/organization/{organization}/campaign/{campaign}/payout', 'Campaign\CampaignsController@update')
        ->name('api.campaign.payout');

    Route::put('/organization/{organization}/campaign/{campaign}/publish', 'Campaign\CampaignsController@publish')
        ->name('api.campaign.publish');

    Route::get('/organization/{organization}/campaign/{campaign}/campaign-statistics', 'Campaign\CampaignsController@campaignStats')
        ->name('api.campaign.campaign-statistics');

    Route::get('/organization/{organization}/campaign/{campaign}/recent-campaign-donations/{limit?}', 'Donation\DonationsController@recentCampaignDonations')
        ->name('api.campaign.recent-campaign-donations');

    Route::put('/organization/{organization}/connected-account/{organizationConnectedAccount}', 'Organization\ConnectedAccountsController@update')
        ->name('api.account.update');

    Route::delete('/organization/{organization}/connected-account/{organizationConnectedAccount}/delete', 'Organization\ConnectedAccountsController@delete')
        ->name('api.account.delete');

    Route::post('/organization/{organization}/invite', 'Organization\InvitationsController@store')
        ->name('api.organization.invite');

    Route::post('/organization/{organization}/check-email', 'Organization\InvitationsController@checkEmail')
        ->name('api.organization.check-email');

    Route::get('/organization/{organization}/connected-accounts', 'Organization\ConnectedAccountsController@index')
        ->name('api.connected-account.index');

    Route::get('/me', 'UsersController@me')
        ->name('api.user.me');

    Route::get('/organization/{organization}/campaigns', 'Campaign\CampaignsController@index')
        ->name('api.campaign.index');

    Route::get('/organization/{organization}/donors', 'Donor\DonorsController@index')
        ->name('api.donor.index');

    Route::get('/organization/{organization}/recent-donors', 'Donor\DonorsController@recentDonors')
        ->name('api.donor.recentDonors');

    Route::get('/organization/{organization}/top-donors', 'Donor\DonorsController@topDonors')
        ->name('api.donor.topDonors');

    Route::get('/organization/{organization}/donation-statistics', 'Donation\DonationsController@donationStats')
        ->name('api.donation.donation-statistics');

    Route::get('/organization/{organization}/donor/{donor}/donations', 'Donation\DonationsController@index')
        ->name('api.donation.index');

    Route::get('/organization/{organization}/donor/{donor}/donor-question-answers', 'DonorQuestion\DonorQuestionAnswersController@index')
        ->name('api.donor.donor-question-answers');

    Route::post('/organization/{organization}/donor', 'Donor\DonorsController@store')
        ->name('api.donor.store');

    Route::get('/organization/{organization}/invited-users', 'Organization\AdminsController@pendingAccountUsers')
        ->name('api.organization.invited-users');

    Route::get('/organization/{organization}/invitation/{invitation}/resend-email', 'Organization\InvitationsController@resendEmail')
        ->name('api.invitation.resend-email');

    Route::delete('/organization/{organization}/invitation/{invitation}', 'Organization\InvitationsController@destroy')
        ->name('api.invitation.delete');

    Route::delete('/organization/{organization}/account-user/{user}', 'Organization\AdminsController@destroy')
        ->name('api.account-user.delete');

    Route::get('/organization/{organization}/campaign-list', 'Campaign\CampaignsController@list')
        ->name('api.campaign.list');

    Route::get('/organization/{organization}/donations', 'Donation\DonationsController@list')
        ->name('api.donation.list');

    Route::get('/organization/{organization}/chart-data', 'Donation\DonationsController@chartData')
        ->name('api.donation.chart-data');

    Route::get('/organization/{organization}/account-list', 'Campaign\PayoutsController@list')
        ->name('api.account.list');

    Route::get('/organization/{organization}/payouts', 'Organization\PayoutsController@index')
        ->name('api.payouts');

    Route::get('/organization/{organization}/campaign/{campaign}/campaign-payouts', 'Campaign\PayoutsController@campaignPayoutList')
        ->name('api.payouts.campaign-payouts');

    Route::post('/organization/{organization}/donor/{donor}/email', 'Donor\DonorsController@emailDonor')
        ->name('api.donor.email');

    /*======================Campaign Category Endpoints=======================*/
    Route::get('/categories', 'Campaign\CampaignsController@categories')
        ->name('api.categories');
    /*==========================Affiliation Endpoints=========================*/
    Route::get('/affiliations', 'Donation\DonationsController@getAffiliations')
        ->name('api.affiliations');

    Route::get('/organization/{organization}/affiliation-donations', 'Organization\Reports\AffiliationDonationsController@index')
        ->name('api.organization.affiliation-donations');
});

/*===================================SUPERADMIN==================================*/

Route::middleware(['auth:api', 'revalidate', 'impersonate.leave'])
    ->prefix('admin')->group(function () {

    Route::get('/stats', 'Admin\DashboardController@stats')
        ->name('api.admin.stats');

    Route::get('/organizations', 'Admin\Organization\OrganizationController@index')
        ->name('api.admin.organizations');

    Route::get('/campaigns', 'Admin\Campaign\CampaignController@index')
        ->name('api.admin.campaigns');

    Route::get('/admins', 'Admin\AdminController@index')
        ->name('api.admin.admins');

    Route::get('/get-admin-users', 'Admin\AdminController@index')
        ->name('api.admin.get-admin-users');

    Route::get('/user/{user}', 'Admin\AdminController@show')
        ->name('api.admin.user');

    Route::post('/user', 'Admin\AdminController@store')
        ->name('api.admin.store');

    Route::post('/user/{user}/update', 'Admin\AdminController@update')
        ->name('api.admin.update');

    Route::put('/user/{user}/delete', 'Admin\AdminController@delete')
        ->name('api.admin.delete');

    Route::get('/organization/{organization}/payout-campaigns', 'Admin\Campaign\CampaignController@payoutCampaigns')
        ->name('api.admin.payout-campaigns');

    Route::get('/campaign/{campaign}/donations', 'Admin\Donation\DonationsController@getDonations')
        ->name('api.admin.campaign.donations');

    Route::post('/payout', 'Admin\Payouts\PayoutsController@store')
        ->name('api.admin.payout');

    Route::post('/organization/create', 'Admin\Organization\OrganizationController@store')
        ->name('api.admin.organization');

    Route::get('/stats/online-donations', 'Admin\Reports\OnlineDonationsController@stats')
        ->name('api.stats.online-donations');

    Route::get('/reports/online-donations', 'Admin\Reports\OnlineDonationsController@index')
        ->name('api.reports.online-donations');

    Route::get('/reports/donation-stats', 'Admin\Reports\DonationStatsController@stats')
        ->name('api.reports.donation-stats');

    Route::get('/reports/monthly-donations', 'Admin\Reports\DonationStatsController@monthlyDonations')
        ->name('api.reports.monthly-donations');

    Route::get('/reports/categories', 'Admin\Reports\CategoryController@getCampaignCategories')
        ->name('api.reports.categories');

    Route::get('/reports/affiliation-donations', 'Admin\Reports\AffiliationDonationsController@index')
        ->name('api.reports.affiliation-donations');
});
