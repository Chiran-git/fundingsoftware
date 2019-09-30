<?php

namespace App;

use App\Traits\Sluggable;
use App\Traits\Presentable;
use Illuminate\Support\Str;
use App\Presenters\CampaignPresenter;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFractionalMonetaryUnits;

class Campaign extends Model
{
    use Sluggable, HasFractionalMonetaryUnits, Presentable;

    /**
     * Various payout methods for the campaign
     */
    const PAYOUT_METHOD_BANK = 'bank';
    const PAYOUT_METHOD_CHECK = 'check';
    const PAYOUT_METHOD_NOT_SET = null;

    /**
     * Presenter used by this model
     *
     * @var string
     */
    protected $presenter = CampaignPresenter::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'organization_id',
        'created_by_id',
        'fundraising_goal',
        'end_date',
        'description',
        'video_url',
        'image',
        'image_filename',
        'image_filesize',
        'payout_method',
        'payout_connected_account_id',
        'payout_schedule',
        'payout_name',
        'payout_organization_name',
        'payout_address1',
        'payout_address2',
        'payout_city',
        'payout_state',
        'payout_zipcode',
        'payout_country_id',
        'payout_payable_to',
        'payout_schedule',
        'funds_raised'
    ];

    public $dates = [
        'end_date',
        'published_at',
    ];

    /**
     * Scope a query to only include published.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now())
            ->whereNull('disabled_at');
    }

    /**
     * Scope a query to only include active campaigns.
     * An active campaign is one which is published, not disabled and
     * end_date is either null or in the future.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('published_at', '<=', now())
            ->whereNull('disabled_at')
            ->where(
                function ($query) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', now());
                }
            );
    }

    /**
     * Fields that are stored in fractional monetary units in database.
     * This is used by HasFractionalMonetaryUnits trait
     *
     * @return array
     */
    public function fractionalMonetaryFields()
    {
        return [
            'fundraising_goal',
            'funds_raised',
        ];
    }

    /**
     * Method to set summary for featured post
     *
     * @return string
     */
    public function getExcerptAttribute()
    {
        // We will use description as summary. Content is in markdown
        // so convert it to html and strip tags. Then get only first 50 words.
        $excerpt = Str::words(strip_tags(\Illuminate\Mail\Markdown::parse($this->description)), 20, '...');

        return $excerpt;
    }

    /**
     * Relation with created by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Method to build relationship between Campaign and Campaign Reward model
     *
     * @return App\CampaignReward
     */
    public function rewards()
    {
        return $this->hasMany(CampaignReward::class);
    }

    /**
     * Method to build relationship between Campaign and Organization
     *
     * @return App\Organization
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the account that owns the campaign.
     *
     * @return App\OrganizationConnectedAccount
     */
    public function connected_account()
    {
        return $this->belongsTo(OrganizationConnectedAccount::class, 'payout_connected_account_id');
    }

    /**
     * Relation with published by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by_id');
    }

    /**
     * Relation with country
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payout_country()
    {
        return $this->belongsTo(Country::class, 'payout_country_id');
    }

    /**
     * Relation with invitation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'campaign_ids');
    }

    /**
     * Campaign has many users
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            CampaignUser::class,
            'campaign_id', // Foreign key on campaign users table...
            'id', // Foreign key on users table...
            'id', // Local key on campaign table...
            'user_id' // Local key on campaign_users table...
        );
    }

    /**
     * Method to build relationship between Campaign and Donation model
     *
     * @return App\Donation
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Method to determine if the current campaign is active or not
     * A campaign is active if its end_date is in the future or null
     *
     * @return boolean
     */
    public function isActive()
    {
        return ! $this->end_date || $this->end_date->greaterThan(now());
    }


    /**
     * Scope a query to only include complete.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeComplete($query)
    {
        return $query->where('published_at', '<=', now())
            ->whereNull('disabled_at')
            ->where('end_date', '<=', now());
    }

}
