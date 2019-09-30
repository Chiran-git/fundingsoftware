<?php

namespace App\Repositories\Contracts;

interface CampaignRewardRepositoryInterface
{
    /**
     * Method to upload an image for the campaign reward
     *
     * @param \Illuminate\Http\UploadedFile $uploadedFile
     * @param string $field
     * @param integer $id
     *
     * @return void
     */
    public function storeImage($uploadPath, $uploadedFile, $id = null);

    /**
     * Method to update/upload an image for the reward. The following image fields
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

    /**
     * Method to update all attributes
     *
     * @param integer $id
     * @param array $data
     *
     * @return void
     */
    public function updateAll($id, $data);
}
