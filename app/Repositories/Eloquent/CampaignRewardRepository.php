<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\CampaignRewardRepositoryInterface;

class CampaignRewardRepository extends Repository implements CampaignRewardRepositoryInterface
{
    /**
     * Method to upload an image for the campaign
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
     * Method to update/upload an image for the campaign reward.
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

        $uploadPath = '/organizations/' . $id;

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
     * Method to update all attributes
     *
     * @param integer $id
     * @param array $data
     *
     * @return void
     */
    public function updateAll($id, $data)
    {
        //update data
        return $this->find($id)->update($data);
    }
}
