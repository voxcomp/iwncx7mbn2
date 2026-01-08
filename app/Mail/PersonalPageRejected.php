<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PersonalPageRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $link;

    public $registrant;

    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($registrant)
    {
        $this->link = config('app.url').'/home';
        $this->registrant = $registrant;
        $this->subject = "Czar's Promise Personal Fundraising Page";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.personalpagerejected')->text('mail.personalpagerejected_plain');
    }
}
