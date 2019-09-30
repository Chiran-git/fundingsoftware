<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailDonor extends Mailable
{
    use Queueable, SerializesModels;

    public $organization;

    public $content;

    public $subject;

    public $user;

    public $donor;    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($organization, $content, $subject, $user, $donor)
    {
        $this->organization = $organization;
        $this->content = $content;
        $this->subject = $subject;
        $this->user = $user;
        $this->donor = $donor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $userName = $this->user->first_name . " " . $this->user->last_name;
        return $this->markdown('emails.en.donor-email')
                    ->replyTo($this->user->email, $userName)
                    ->from(config('app.email'),  $userName)
                    ->subject($this->subject);
    }
}
