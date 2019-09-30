<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invitation extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'campaign_ids' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'campaign_ids',
        'invitee_id',
        'first_name',
        'last_name',
        'email',
        'role',
        'code',
        'expires_at',
    ];

    /**
     * Relation with organization
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Relation with created by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }

    /**
     * Relation with created by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the campaign that owns the invitation.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
