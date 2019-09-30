<?php

namespace App\Jobs\Campaign;

use App\User;
use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\Campaign\CampaignWasCreated;
use App\Repositories\Contracts\CampaignRepositoryInterface;

class Create implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Organization
     *
     * @var \App\Organization
     */
    public $organization;

    /**
     * User
     *
     * @var \App\User
     */
    public $user;

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
    public function __construct(Organization $organization, User $user, $data)
    {
        $this->data = $data;
        $this->organization = $organization;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @param \App\Repositories\Contracts\CampaignRepositoryInterface $campaignRepo
     *
     * @return void
     */
    public function handle(CampaignRepositoryInterface $campaignRepo)
    {
        $imagePath = "";
        //upload image
        if (!empty($this->data['image'])) {
            $uploadPath = '/organizations/' . $this->organization->id;
            $image = $this->data['image'];
            if(! $imagePath = $campaignRepo->updateImage($uploadPath, $image)) {
                return false;
            }
        }
        if ($campaign = $campaignRepo->store(
            [
                'organization_id' => $this->organization->id,
                'created_by_id' => $this->user->id,
                'name' => $this->data['name'],
                'fundraising_goal' => $this->data['fundraising_goal'],
                'end_date' => ! empty($this->data['end_date']) ? Carbon::parse($this->data['end_date']) : null,
                'video_url' => ! empty($this->data['video_url']) ? $this->data['video_url'] : null,
                'description' => $this->data['description'],
                'image' => $imagePath ? $imagePath : null,
                'image_filename' => $imagePath ? $image->getClientOriginalName() : null,
                'image_filesize' => $imagePath ? $image->getFileInfo()->getSize() : null,
            ]
        )) {
            event( new CampaignWasCreated($campaign));
            return $campaign;
        }
        if (!empty($imagePath)) {
            $campaignRepo->deleteImageFromStorage('public_uploads', $imagePath);
        }
        return false;
    }

}
