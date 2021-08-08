<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends Repository implements UserRepositoryInterface
{
    /**
     * Method to create a new user if the user with given email doesn't exist
     *
     * @param array $attributes User attributes
     *
     * @return App\User New User or existing user object
     */
    function createIfNotExists($attributes)
    {
        if ($user = $this->findWhere(['email' => $attributes['email']])) {
            return $user;
        }

        return $this->store($attributes);
    }


    /**
     * Method to update account users those accepted invitations
     *
     * @param array $attributes User attributes
     *
     * @return App\User Existing user object
     */
    function updateAccountUser($id, $attributes)
    {
        return $this->update($id,
            [
                "first_name" => $attributes['first_name'],
                "last_name" => $attributes['last_name'],
                "email" => $attributes['email']
            ]
        );
    }

    /**
     * Method to update existing
     *
     * @param array $attributes User attributes
     *
     * @return App\User New User or existing user object
     */
    function updateUser($id, $attributes)
    {
        return $this->update($id,
            [
                "first_name" => $attributes['first_name'],
                "last_name" => $attributes['last_name'],
                "email" => $attributes['email'],
                "job_title" => $attributes['job_title']
            ]
        );
    }

    /**
     * Method to upload an image for fresh user
     *
     * @param \Illuminate\Http\UploadedFile $uploadedFile
     * @param string $field
     * @param integer $id
     *
     * @return void
     */
    public function storeImage($uploadPath, $uploadedFile, $id = null)
    {
        $options = ['disk' => 'public_uploads', 'visibility' => 'public'];

        // Find the old image (if any) which will need to be deleted
        $oldImage = !empty($id) ? $this->find($id)->{'image'} : null;

        // Upload the image using "uploads" disk (s3)
        if ($imagePath = $this->uploadImage($uploadedFile, $uploadPath, $options)) {

            if ($oldImage) {

                $this->deleteImageFromStorage($options['disk'], $oldImage);
            }

            return $imagePath;
        }

        return false;
    }

    /**
     * Method to update/upload an image for the user.
     * The following image fields are supported as of now
     * - image
     *
     * @param integer $id
     * @param \Illuminate\Http\UploadedFile $uploadedFile
     * @param string $field
     *
     * @return void
     */
    public function updateImage($id, $uploadedFile, $field)
    {
        if (! in_array($field, ['image'])) {
            return false;
        }

        $uploadPath = '/users';

        $options = ['disk' => 'public_uploads', 'visibility' => 'public'];

        // Find the old image (if any) which will need to be deleted
        $oldImage = $this->find($id)->{$field};

        // Upload the image using "uploads" disk (s3)
        if ($imagePath = $this->uploadImage($uploadedFile, $uploadPath, $options)) {

            // Update the image fields in the organization.
            $updated = $this->update(
                $id,
                [
                    $field => $imagePath,
                    "{$field}_filename" => $uploadedFile->getClientOriginalName(),
                    "{$field}_filesize" => $uploadedFile->getFileInfo()->getSize(),
                ]
            );

            if ($updated && $oldImage) {

                return $this->deleteImageFromStorage($options['disk'], $oldImage);
            }

            return $updated;
        }

        return false;
    }

    /**
     * Method to delete image
     *
     * @param integer $id
     *
     * @return void
     */
    public function deleteImage($id)
    {
        $imagePath = $this->find($id)->{'image'};
        $options = ['disk' => 'public_uploads', 'visibility' => 'public'];
        if ($this->deleteImageFromStorage($options['disk'], $imagePath)) {
            $updated = $this->update(
                $id,
                [
                    "image" => null,
                    "image_filename" => null,
                    "image_filesize" => null
                ]
            );
            if ($updated) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the admin users list.
     *
     * @param integer $limit
     * @param boolean $pagination
     * @param array   $params
     *
     * @return array
     */
    public function getAdminUsersList($limit = 10, $pagination = true)
    {
        $query = $this->model
            ->select('id','first_name', 'last_name', 'email', 'user_type')
            ->whereNotIn('user_type', ['organization']);

        if ($pagination) {
            $sortOptions = json_decode(request()->query('sort'));
            if (isset($sortOptions->fieldName) && ! empty($sortOptions->fieldName)
                && isset($sortOptions->order) && ! empty($sortOptions->order)) {
                $query->orderBy($sortOptions->fieldName, $sortOptions->order);
            } else {
                $query->orderBy('created_at', 'asc');
            }

            return $query->paginate($limit);
        } else {
            $query->orderBy('created_at', 'asc');
            return $query->get();
        }
    }

}
