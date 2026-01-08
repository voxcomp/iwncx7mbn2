<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SponsorAdmin extends Mailable
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
	    $this->subject = "New ".$event->short." Event Sponsor";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    if(!empty($this->submission->image)) {
		    $storagePath  = \Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
		    
	        return $this->view('mail.sponsorAdmin')->attach($storagePath."/".$this->submission->image);
	    } else {
		    return $this->view('mail.sponsorAdmin');
	    }
    }
}
