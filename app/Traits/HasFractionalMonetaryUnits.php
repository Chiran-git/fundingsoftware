<?php

namespace App\Traits;

trait HasFractionalMonetaryUnits
{
    /**
     * Fractional monetary fields
     *
     * @var array
     */
    public $fractionalMonetaryFields = [];

    /**
     * Method to get the array of fractional monetary fields
     *
     * This method should be overriden in the model where this trait is used
     * and it should return the array of that model specific fields
     *
     * @return array
     */
    public function fractionalMonetaryFields()
    {
        return $this->$fractionalMonetaryFields;
    }

    /**
     * Boot this trait. Called by eloquent model automatically.
     *
     * @return void
     */
    public static function bootHasFractionalMonetaryUnits()
    {
        static::saving(function ($model) {
            // Before saving the model, we need to convert the whole monetary values to fractional
            foreach ($model->fractionalMonetaryFields() as $field) {
                if (! empty($model->{$field})) {
                    // Removing comma
                    $model->{$field} = preg_replace('/,/', '', $model->{$field});
                    // Convert the whole monetary value (Ex. 10.50) to
                    // fractional monetary value (1050)
                    $model->{$field} = round($model->{$field} * 100);
                }
            }
        });

        // Called when model is retrieved
        static::retrieved(function ($model) {
            foreach ($model->fractionalMonetaryFields() as $field) {
                if (! empty($model->{$field})) {
                    $fractionalValue = $model->{$field};
                    // Convert fractional to whole unit and store in a separate field
                //    $model->{$field . '_fractional'} = $fractionalValue; //commenting as it is giving sql error while updating
                    $model->{$field} = round($fractionalValue / 100, 2);
                }
            }
        });


    }
}
