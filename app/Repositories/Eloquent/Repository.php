<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\Storage;

abstract class Repository
{
    /**
     * @var Model instance
     */
    protected $model;

    /**
     * Constructor
     *
     * @param \Illuminate\Database\Eloquent\Model $model Model instance
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Return all models in a collection
     *
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Create a new record
     *
     * @param array $attributes Attributes for the record to be created
     *
     * @return mixed
     */
    public function store($attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Update an existing model
     *
     * @param integer $id         Id of the model to be updated
     * @param array   $attributes Attributes to be updated
     *
     * @return mixed
     */
    public function update($id, $attributes)
    {
        return $this->model
            ->whereId($id)
            ->update($attributes);
    }

    /**
     * Delete a model
     *
     * @param integer $id Id of the model to be deleted
     *
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model
            ->destroy($id);
    }

    /**
     * Get a model with a specific idea
     *
     * @param integer $id Id of the model to be fetched
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Search for object with supplied properties, and return
     * either the first object or null
     *
     * @param array $cols Array of fields to be used as where clause
     *
     * @return mixed
     */
    public function findWhere($cols = [])
    {
        return $this->model
            ->where($cols)
            ->first();
    }

    /**
     * Search for objects with supplied properties, and return
     * the collection
     *
     * @param array $cols Array of fields to be used as where clause
     *
     * @return mixed
     */
    public function findAllWhere($cols = [])
    {
        return $this->model
            ->where($cols)
            ->get();
    }

    /**
     * Return models with an ID in the supplied array
     *
     * @param array $ids Array of Ids to be used as where clause
     *
     * @return mixed
     */
    public function getWhereIdIn($ids = [])
    {
        return $this->model
            ->whereIn('id', $ids)
            ->get();
    }

    /**
     * Determine if object with the given ID belongs to a foreign
     * object with another ID
     *
     * @param int    $id              Id of the model
     * @param string $foreignIdColumn Foreign Id field name
     * @param int    $foreignId       Foreign id
     *
     * @return bool
     */
    public function belongsTo($id, $foreignIdColumn, $foreignId)
    {
        return $this->model
            ->whereId($id)
            ->where($foreignIdColumn, $foreignId)
            ->exists();
    }

    /**
     * Check if a model with this ID exists
     *
     * @param integer $id Id of the model to check if it exists
     *
     * @return bool
     */
    public function exists($id)
    {
        return $this->model
            ->whereId($id)
            ->exists();
    }

    /**
     * Determine if a model with a specific column value exists
     *
     * @param mixed $col   Column name
     * @param mixed $value Value
     *
     * @return bool
     */
    public function existsWhere($col, $value = null)
    {
        return $this->model
            ->where($col, $value)
            ->exists();
    }

    /**
     * Method to upload an image.
     *
     * @param \Illuminate\Http\UploadedFile $uploadedFile
     * @param string $uploadPath
     * @param array  $options
     *
     * @return void
     */
    public function uploadImage($uploadedFile, $uploadPath, $options)
    {

        // Upload the image using "uploads" disk (s3)
        if ($imagePath = $uploadedFile->store($uploadPath, $options)) {

            return $imagePath;
        }

        return false;
    }

    /**
     * Method to delete the image from storage
     *
     * @param string $image
     *
     * @return boolean
     */
    public function deleteImageFromStorage($disk, $image)
    {
        return Storage::disk($disk)->delete($image);
    }

    /**
     * Search for objects with supplied properties, and return
     * the query builder
     *
     * @param array $cols Array of fields to be used as where clause
     *
     * @return mixed
     */
    public function findAllQueryWhere($cols = [])
    {
        return $this->model
            ->where($cols);
    }
}
