<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JoinTeamCaptain extends Mailable
{
    use Queueable, SerializesModels;

    public $team;

    public $registrant;

    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($team, $registrant)
    {
        $this->team = $team;
        $this->registrant = $registrant;
        $this->subject = 'New Team Member';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.jointeamcaptain')->text('mail.jointeamcaptain_plain');
    }
}
