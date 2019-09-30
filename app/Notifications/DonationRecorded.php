<?php

namespace App\Notifications;

use App\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DonationRecorded extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Donation that was made
     *
     * @var \App\Donation
     */
    public $donation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
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
        // get organization
        $organization = $this->donation->organization()->first();
        // get campaign
        $campaign = $this->donation->campaign()->first();
        // get donor
        $donor =$this->donation->donor()->first();

        return (new MailMessage)
                ->subject(__('Thank you for your contribution to :campaign', ['campaign' => $campaign->name]))
                ->from(config('app.email'), $organization->name)
                ->markdown('emails.en.donation', [
                    'donation' => $this->donation,
                    'organization' => $organization,
                    'campaign' => $campaign,
                    'donor' => $donor,
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
