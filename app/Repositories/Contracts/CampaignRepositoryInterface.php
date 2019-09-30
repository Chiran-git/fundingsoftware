<?php

namespace App\Repositories\Contracts;

interface CampaignRepositoryInterface
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
    public function updateImage($uploadPath, $uploadedFile, $id = null);

    /**
     * Method to update all attributes
     *
     * @param integer $id
     * @param array $data
     *
     * @return void
     */
    public function updateAll($id, $data);

    /**
     * Method to find campaign-admins of organization
     *
     * @param App\Organization $organizationId
     * @param App\User $userId
     *
     * @return App\Campaign
     */
    public function findAllCampaignsForCampaignAdmin($organizationId, $userId, $limit);


    /**
     * Method to deactivate the campaign
     *
     * @param integer $id Campaign id
     * @param array $data
     *
     * @return boolean
     */
    public function deactivate($id, $data);

}
