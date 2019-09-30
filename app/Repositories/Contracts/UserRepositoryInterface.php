<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    /**
     * Method to create a new user if the user with given email doesn't exist
     *
     * @param array $attributes User attributes
     *
     * @return App\User New User or existing user object
     */
    function createIfNotExists($attributes);

    /**
     * Method to update account users those accepted invitations
     *
     * @param $id
     * @param array $attributes User attributes
     *
     * @return App\User Existing user object
     */
    function updateAccountUser($id, $attributes);

    /**
     * Method to update a user
     *
     * @param $id
     * @param array $attributes User attributes
     *
     * @return App\User Existing user object
     */
    function updateUser($id, $attributes);

    /**
     * Method to upload an image for fresh user
     *
     * @param \Illuminate\Http\UploadedFile $uploadedFile
     * @param string $field
     * @param integer $id
     *
     * @return void
     */
    public function storeImage($uploadPath, $uploadedFile, $id = null);

    /**
     * Method to update/upload an image for the user. The following image fields
     * are supported as of now
     * - image
     *
     * @param integer $id
     * @param \Illuminate\Http\UploadedFile $uploadedFile
     * @param string $field
     *
     * @return void
     */
    public function updateImage($uploadPath, $uploadedFile, $field);

    /**
     * Method to delete image
     *
     * @param integer $id
     *
     * @return void
     */
    public function deleteImage($id);
}
