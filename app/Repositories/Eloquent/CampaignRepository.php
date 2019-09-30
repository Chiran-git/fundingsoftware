<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\CampaignRepositoryInterface;

class CampaignRepository extends Repository implements CampaignRepositoryInterface
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
    public function updateImage($uploadPath, $uploadedFile, $id = null)
    {
        $options = ['disk' => 'public_uploads', 'visibility' => 'public'];

        // Find the old image (if any) which will need to be deleted
        $oldImage = !empty($id) ? $this->find($id)->{'image'} : null;

        // Upload the image using "uploads" disk (s3)
        if ($imagePath = $this->uploadImage($uploadedFile, $uploadPath, $options)) {

            if ($oldImage) {

                return $this->deleteImageFromStorage($options['disk'], $oldImage);
            }

            return $imagePath;
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

    /**
     * Method to find all users organization
     *
     * @param App\Organization $organizationId
     * @param App\User $userId
     *
     * @return App\OrganizationUser
     */
    public function getCampaignListing($organizationId, $userId)
    {
        // Get all admins and owner of organization
        $query = $this->model->where('campaigns.organization_id', $organizationId);

        if (!empty($userId)) {
            $query->join('campaign_users', 'campaigns.id', '=', 'campaign_users.campaign_id')
                ->where('campaign_users.user_id', $userId);
        }

        return $query->pluck('campaigns.name', 'campaigns.id');

    }

    /* Method to find campaign-admins of organization
     *
     * @param App\Organization $organizationId
     * @param App\User $userId
     *
     * @return App\Campaign
     */
    public function findAllCampaignsForCampaignAdmin($organizationId, $userId, $status=null, $limit = 10)
    {
        if ($status == 'active') {
            return $this->model->where('campaigns.organization_id', $organizationId)
                ->join('campaign_users', 'campaigns.id', '=', 'campaign_users.campaign_id')
                ->where('campaign_users.user_id', $userId)
                ->whereNull('campaigns.deleted_at')
                ->whereNull('campaign_users.deleted_at')
                ->whereNull('campaigns.disabled_at')
                ->where('campaigns.published_at', '<=', now())
                ->where(
                        function ($query) {
                            $query->whereNull('campaigns.end_date')
                                    ->orWhere('campaigns.end_date', '>=', now());
                        }
                    )
                ->select('campaigns.*')
                ->orderBy('campaigns.created_at', 'desc')
                ->paginate($limit);
        } else {
            return $this->model->where('campaigns.organization_id', $organizationId)
                ->join('campaign_users', 'campaigns.id', '=', 'campaign_users.campaign_id')
                ->where('campaign_users.user_id', $userId)
                ->whereNull('campaigns.deleted_at')
                ->whereNull('campaign_users.deleted_at')
                ->select('campaigns.*')
                ->orderBy('campaigns.created_at', 'desc')
                ->paginate($limit);
        }
    }

    /**
     * Method to deactivate the campaign
     *
     * @param integer $id Campaign id
     * @param array $data
     *
     * @return boolean
     */
    public function deactivate($id, $data)
    {
        if ($data) {
            return $this->update(
                $id,
                $data
            );
        }

        return false;
    }
}
