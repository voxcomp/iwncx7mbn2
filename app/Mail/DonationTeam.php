<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonationTeam extends Mailable
{
    use Queueable, SerializesModels;

    public $amount;

    public $fname;

    public $lname;

    public $email;

    public $anonymous;

    public $comment;

    public $subject;

    public $link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($amount, $fname, $lname, $email, $comment, $anonymous)
    {
        $this->amount = $amount;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->anonymous = $anonymous;
        $this->comment = $comment;
        $this->subject = 'Your Team has received a donation!';
        $this->link = config('app.url').'/login';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->view('mail.donationteam')->text('mail.donationteam_plain');
    }
}
