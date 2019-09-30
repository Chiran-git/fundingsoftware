<?php

namespace App\Traits;

use Illuminate\Support\Str;
use App\Exceptions\PresenterException;

trait Sluggable
{
    /**
     * Field which holds the slug in the database
     *
     * @var string
     */
    public $slugFromField = 'name';

    /**
     * Field which holds the slug in the database
     *
     * @var string
     */
    public $slugField = 'slug';

    /**
     * Boot sluggable trait. Called by eloquent model automatically.
     *
     * @return void
     */
    public static function bootSluggable()
    {
        static::saving(function ($model) {
            // Sluggify only if slug is not already present
            if (! $model->{$model->slugField}) {
                $model->{$model->slugField} = $model->slugify($model->{$model->slugFromField});
            }
        });
    }

    /**
     * Method to slugify the slugFrom field
     *
     * @param string $name String to slugify
     *
     * @return string
     */
    public function slugify($name)
    {
        $reserved = $this->getAllRouteUris();

        // Get the slug of the name
        $slug = Str::slug($name);

        // If it's a reserved word (i.e. one of the app URIs)
        if (in_array($slug, $reserved)) {
            // Add a random number to it
            $slug = $slug . '-' . rand(10, 100);
        }

        return $this->getUniqueSlug($slug);
    }

    /**
     * Method to get all registered URIs
     *
     * @return array Array with all URIs
     */
    private function getAllRouteUris()
    {
        $return = [];

        $routes = app()->routes->getRoutes();

        foreach ($routes as $route) {
            $return[] = $route->uri;
        }

        return $return;
    }

    /**
     * Method to find the unique slug.
     *
     * This checks if any record in the db has the same slug.
     * If it does, then it adds -1 to the slug and checks again and keeps
     * incrementing the counter till we find a unique slug.
     *
     * @param string $slug The slug
     *
     * @return string Unique slug
     */
    private function getUniqueSlug($slug)
    {
        $uniqueSlug = $slug;
        $i = 1;

        while (self::orWhere($this->slugField, $uniqueSlug)->exists()) {
            $uniqueSlug = $slug . '-' . $i;
            $i++;
        }

        return $uniqueSlug;
    }

}
