<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Sponsor extends Mailable
{
    use Queueable, SerializesModels;
	public $submission;
	public $event;
	public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($submission,$event)
    {
	    $this->submission = $submission;
	    $this->event = $event;
	    $this->subject = $event->short." Event Sponsorship";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.sponsor');
    }
}
