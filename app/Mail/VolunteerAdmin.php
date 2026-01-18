<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VolunteerAdmin extends Mailable
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
    public function __construct($submission, $event)
    {
        $this->submission = $submission;
        $this->event = $event;
        $this->subject = 'New '.$event->short.' Volunteer';
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->view('mail.volunteerAdmin');
    }
}
