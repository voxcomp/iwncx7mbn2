<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamPageRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $link;

    public $team;

    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($team)
    {
        $this->link = config('app.url').'/home';
        $this->team = $team;
        $this->subject = "Czar's Promise Team Fundraising Page";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->view('mail.teampagerejected')->text('mail.teampagerejected_plain');
    }
}
