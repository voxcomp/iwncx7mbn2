<?php

namespace App\Mail;

use App\Donation;
use App\Event;
use App\Registrant;
use App\Team;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $event;

    public $registrant;

    public $team;

    public $donation;

    public $user;

    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event, $registrant, $team, $donation, $user)
    {
        $this->event = Event::where('slug', $event)->first();
        $this->registrant = Registrant::where('slug', $registrant)->first();
        $this->team = ($team == 0) ? null : Team::where('slug', $team)->first();
        $this->donation = ($donation == 0) ? null : Donation::where('id', $donation)->first();
        $this->user = ($user == 0) ? null : User::where('slug', $user)->first();

        $this->subject = $this->event->short.' Registration';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.registrationadmin');
    }
}
