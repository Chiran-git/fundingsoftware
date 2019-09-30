<?php

namespace App\Jobs\Organization;

use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\OrganizationRepositoryInterface;

class UpdateDesign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $imageFields = [
        'cover_image',
        'logo',
        'appeal_photo',
    ];

    /**
     * Property to hold org data to update
     *
     * @var array
     */
    public $data;

    /**
     * Organization to update
     *
     * @var \App\Organization
     */
    public $organization;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Organization $organization, $data)
    {
        $this->data = $data;
        $this->organization = $organization;
    }

    /**
     * Execute the job.
     *
     * @param \App\Repositories\Contracts\OrganizationRepositoryInterface $repo
     *
     * @return boolean
     */
    public function handle(OrganizationRepositoryInterface $repo)
    {
        $this->updateImages($repo);

        // Update the other attributes
        return $repo->update(
            $this->organization->id,
            [
                'primary_color' => $this->data['primary_color'],
                'secondary_color' => $this->data['secondary_color'],
                'appeal_headline' => $this->data['appeal_headline'],
                'appeal_message' => $this->data['appeal_message'],
            ]
        );
    }


    /**
     * Update the image fields for the org
     *
     * @param \App\Repositories\Contracts\OrganizationRepositoryInterface $repo
     *
     * @return boolean
     */
    private function updateImages($repo)
    {
        foreach ($this->imageFields as $field) {
            // If we have the field in the data
            if (! empty($this->data[$field])) {
                $repo->updateImage(
                    $this->organization->id,
                    $this->data[$field],
                    $field
                );
            }
        }
    }
}
