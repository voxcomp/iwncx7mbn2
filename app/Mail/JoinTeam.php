<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JoinTeam extends Mailable
{
    use Queueable, SerializesModels;

    public $team;

    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($team)
    {
        $this->team = $team;
        $this->subject = "You've joined ".$team->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.jointeam')->text('mail.jointeam_plain');
    }
}
