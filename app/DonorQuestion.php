<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonorQuestion extends Model
{
    use SoftDeletes;
    /**
     * Attributes that can be mass assigned
     *
     * @var array
     */
    public $fillable = [
        'organization_id',
        'question',
        'type',
        'sort_order',
    ];

    /**
     * Scope a query to only include published.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->whereNull('disabled_at');
    }
}
