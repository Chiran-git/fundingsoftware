<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, SoftDeletes, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'user_type', 'password', 'last_login_at', 'image', 'image_filename', 'image_filesize'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Property to hold the current Organization of the user
     *
     * @var [type]
     */
    public $organization = null;

    /**
     * Property to hold the current role
     *
     * @var string
     */
    public $role = null;

    /**
     * Relation with organizations
     *
     * A single user can belong to many organizations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_users')
            ->withPivot('role', 'deleted_at', 'created_at', 'updated_at')
            ->wherePivot('deleted_at', null)
            ->withTimestamps();
    }

    /**
     * Method to build relationship between Campaign
     *
     * A single user can belong to many campaigns
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_users')
            ->withPivot('organization_id', 'deleted_at', 'created_at', 'updated_at')
            ->wherePivot('deleted_at', null)
            ->withTimestamps();
    }

    /**
     * Computed attribute to get the full name of the user
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Method to get the assigned campaigns (for campaign admin)
     * for the given organization
     *
     * @param Organization $organization
     *
     * @return Collection
     */
    public function getAssignedCampaignsForOrganization($organization)
    {
        return $this->campaigns()
            ->wherePivot('organization_id', $organization->id)
            ->get();
    }

    /**
     * Method to determine if current user is super admin
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->user_type === 'superadmin';
    }

    /**
     * Method to determine if current user is super admin
     *
     * @return boolean
     */
    public function isAppAdmin()
    {
        return $this->user_type === 'appadmin';
    }

    /**
     * Method to check if the user can impersonate
     *
     * @return bool
     */
    public function canImpersonate()
    {
        if ( $this->isSuperAdmin() || $this->isAppAdmin() ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function canBeImpersonated()
    {
        return $this->isOrganization();
    }

    /**
     * Method to determine if current user is an organization user
     *
     * @return boolean
     */
    public function isOrganization()
    {
        return $this->user_type === 'organization';
    }

    /**
     * Method to get the current active organization for the user
     *
     * @return App\Organization
     */
    public function currentOrganization()
    {
        return $this->organizations()->first();
    }

    /**
     * Metthod to get the current role of the user
     * Role can be:
     *  - owner
     *  - admin
     *  - campaign-admin
     *
     * @return string
     */
    public function currentRole()
    {
        return $this->currentOrganization()->pivot->role;
    }

    /**
     * Method to find the user associated organization
     *
     * @param integer $organizationId
     *
     * @return mixed \App\Organization or null
     */
    public function findAssociatedOrganization($organizationId)
    {
        return $this->organizations()->where('organization_id', $organizationId)
            ->first();
    }

    /**
     * Method to find the user associated organization campaign
     *
     * @param integer $organizationId
     *
     * @return mixed \App\Organization or null
     */
    public function findAssociatedOrganizationCampaign($campaignId)
    {
        return $this->campaigns()->where('campaign_id', $campaignId)
            ->first();
    }
}
