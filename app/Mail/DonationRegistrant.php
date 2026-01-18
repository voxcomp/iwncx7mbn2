<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonationRegistrant extends Mailable
{
    use Queueable, SerializesModels;

    public $amount;

    public $fname;

    public $lname;

    public $email;

    public $anonymous;

    public $event;

    public $comment;

    public $subject;

    public $link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($amount, $fname, $lname, $email, $anonymous, $event, $comment)
    {
        $this->amount = $amount;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->comment = $comment;
        $this->anonymous = $anonymous;
        $this->event = $event;
        $this->subject = 'You have received a donation!';
        $this->link = config('app.url').'/login';
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->view('mail.donationregistrant')->text('mail.donationregistrant_plain');
    }
}
