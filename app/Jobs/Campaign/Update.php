<?php

namespace App\Jobs\Campaign;

use App\Campaign;
use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\Campaign\CampaignWasUpdated;
use App\Repositories\Contracts\CountryRepositoryInterface;
use App\Repositories\Contracts\CampaignRepositoryInterface;

class Update implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Organization
     *
     * @var \App\Organization
     */
    public $organization;

    /**
     * Campaign
     *
     * @var \App\Campaign
     */
    public $campaign;

    /**
     * Campaign data
     *
     * @var array
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Organization $organization, Campaign $campaign, $data)
    {
        $this->data = $data;
        $this->organization = $organization;
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @param \App\Repositories\Contracts\CampaignRepositoryInterface $campaignRepo
     *
     * @return void
     */
    public function handle(CampaignRepositoryInterface $campaignRepo, CountryRepositoryInterface $countryRepo)
    {
        $imagePath = "";
        $oldImage = "";
        $fields = $this->data;

        //if image is present withiout value then set null for image field
        if (array_key_exists('image', $fields)) {

            $fields['image'] = null;
            $fields['image_filename'] = null;
            $fields['image_filesize'] = null;

            //get old image name to delete
            $oldImage = $this->campaign->image;

            if (!empty($this->data['image'])) {
                $uploadPath = '/organizations/' . $this->organization->id;

                //upload image to s3
                $options = ['disk' => 'public_uploads', 'visibility' => 'public'];
                $image = $this->data['image'];
                if (! $imagePath = $campaignRepo->uploadImage($image, $uploadPath, $options)) {
                    return false;
                }
                $fields['image'] = $imagePath;
                $fields['image_filename'] = $image->getClientOriginalName();
                $fields['image_filesize'] = $image->getFileInfo()->getSize();
            }
        }
        //get country id from country code
        if (isset($fields['payout_country']) && !empty($fields['payout_country'])) {
            $fields['payout_country_id'] = $countryRepo->findByCode($fields['payout_country'])->id;
            unset($fields['payout_country']);
        }
        // Update all the attributes
        if ($campaignRepo->updateAll(
            $this->campaign->id,
            $fields
        )) {
            if (! empty($oldImage)) {
                //delete old image
                $campaignRepo->deleteImageFromStorage('public_uploads', $oldImage);
            }
            event (new CampaignWasUpdated($this->campaign));
            return $this->campaign;
        }
        if (! empty($imagePath)) {
            $campaignRepo->deleteImageFromStorage('public_uploads', $imagePath);
        }
        return false;
    }

}
