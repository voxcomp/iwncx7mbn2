<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonationAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $fname;

    public $lname;

    public $email;

    public $phone;

    public $address;

    public $city;

    public $state;

    public $zip;

    public $comment;

    public $amount;

    public $recurring;

    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fname, $lname, $email, $phone, $address, $city, $state, $zip, $comment, $amount, $recurring)
    {
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
        $this->amount = $amount;
        $this->comment = $comment;
        $this->recurring = $recurring;
        $this->subject = "Donation to Czar's Promise";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.donationAdmin')->text('mail.donationAdmin_plain');
    }
}
