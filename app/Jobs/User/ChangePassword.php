<?php

namespace App\Jobs\User;

use Auth;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\UserRepositoryInterface;

class ChangePassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        if(Hash::check($this->data->old_password, $this->user->password)) {
            return $repo->update($this->user->id, 
                [
                    'password' => bcrypt($this->data->password),
                ]
            );
        }

        return false;
    }
}
