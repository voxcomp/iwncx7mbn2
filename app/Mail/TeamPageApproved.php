<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamPageApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $link;

    public $shortlink;

    public $team;

    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($team)
    {
        $this->link = $team->pageurl;
        $this->shortlink = $team->pageshorturl;
        $this->team = $team;
        $this->subject = "Czar's Promise Team Fundraising Page";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.teampageapproved')->text('mail.teampageapproved_plain');
    }
}
