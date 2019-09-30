<?php

namespace App\Jobs\Admin;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\UserRepositoryInterface;

class Update implements ShouldQueue
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
     * Input data variable
     *
     * @var raay
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $data)
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

        // Update the other attributes
        return $repo->updateAccountUser(
            $this->user->id,
            [
                'first_name' => $this->data['first_name'],
                'last_name' => $this->data['last_name'],
                'email' => $this->data['email'],
            ]
        );
    }

    /**
     * Update the image fields for the user
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
