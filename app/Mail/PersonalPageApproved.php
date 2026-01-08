<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PersonalPageApproved extends Mailable
{
    use Queueable, SerializesModels;
	
	public $link;
	public $shortlink;
	public $registrant;
	public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($registrant)
    {
        $this->link = $registrant->pageurl;
        $this->shortlink = $registrant->pageshorturl;
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
        return $this->view('mail.personalpageapproved')->text('mail.personalpageapproved_plain');;
    }
}
