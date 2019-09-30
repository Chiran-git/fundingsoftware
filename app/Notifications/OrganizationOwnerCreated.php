<?php

namespace App\Notifications;

use App\User;
use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrganizationOwnerCreated extends Notification
{
    use Queueable;

    /**
     * User that was made
     *
     * @var \App\User
     */
    public $user;

    /**
     * Organization that was made
     *
     * @var \App\User
     */
    public $organization;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Organization $organization)
    {
        $this->user = $user;
        $this->organization = $organization;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // get user
        $user = $this->user;

        // get organization
        $organization = $this->organization;

        // Generate a new reset password token
        $token = app('auth.password.broker')->createToken($user);

        return (new MailMessage)
                    ->subject(__('You have been added as Admin to :organization', ['organization' => $organization->name]))
                    ->markdown('emails.en.organization-owner-created', [
                        'user' => $user,
                        'organization' => $organization,
                        'token' => $token
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
