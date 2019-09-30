<?php

namespace App\Notifications;

use App\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Repositories\Contracts\CampaignRepositoryInterface;

class JoinCampaign extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Invitation that was made
     *
     * @var \App\Invitation
     */
    public $invitation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
        $this->queue = 'email';
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
        //get invitee
        $invitee =$this->invitation->invitee()->first();
        //get organization
        $organization = $this->invitation->organization()->first();

        //get campaigns collection for invitations
        $campaignRepo = app(CampaignRepositoryInterface::class);
        $campaigns = $campaignRepo->getWhereIdIn($this->invitation->campaign_ids);

        return (new MailMessage)
            //->from(config('app.from'))
            ->subject(__(':organization added you as an Admin', ['organization' => $organization->name]))
            ->markdown('emails.en.join-campaign', [
                'invitation' => $this->invitation,
                'invitee' => $invitee,
                'campaigns' => $campaigns,
                'organization' => $organization
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
