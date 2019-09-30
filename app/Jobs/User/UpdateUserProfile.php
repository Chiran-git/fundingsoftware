<?php

namespace App\Jobs\User;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\UserRepositoryInterface;

class UpdateUserProfile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $imageFields = [
        'image'
    ];

    /**
     * User
     *
     * @var \App\User
     */
    public $user;

    /**
     * User data
     *
     * @var array
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserRepositoryInterface $repo)
    {
        $this->updateImages($repo);

        return $repo->updateUser($this->user->id, $this->data);
    }

    /**
     * Update the image fields for the reward
     *
     * @return boolean
     */
    private function updateImages($repo)
    {
        foreach ($this->imageFields as $field) {
            // If we have the field in the data
            if(array_key_exists($field, $this->data)){
                if (! empty($this->data[$field])) {
                    $repo->updateImage(
                        $this->user->id,
                        $this->data[$field],
                        $field
                    );
                } else {
                    $repo->deleteImage($this->user->id);
                }
            }           
        }
    }
}
