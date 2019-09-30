<?php

namespace App\Jobs\Admin;

use Illuminate\Bus\Queueable;
use App\Events\Admin\AdminWasCreated;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\UserRepositoryInterface;

class Create implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserRepositoryInterface $userRepo)
    {
        $imagePath = "";
        //upload image
        if (!empty($this->data['image'])) {
            $uploadPath = '/users';
            $image = $this->data['image'];
            if(! $imagePath = $userRepo->storeImage($uploadPath, $image)) {
                return false;
            }
        }

        $user = $userRepo->store(
            [
                'first_name' => $this->data['first_name'],
                'last_name' => $this->data['last_name'],
                'email' => $this->data['email'],
                'password' => bcrypt($this->data['password']),
                'user_type' => 'appadmin',
                'image' => $imagePath ? $imagePath : null,
                'image_filename' => $imagePath ? $image->getClientOriginalName() : null,
                'image_filesize' => $imagePath ? $image->getFileInfo()->getSize() : null
            ]
        );

        if ($user) {
            event(new AdminWasCreated($user));
            return $user;
        } else {
            return false;
        }
    }
}
