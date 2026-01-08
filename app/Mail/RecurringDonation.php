<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecurringDonation extends Mailable
{
    use Queueable, SerializesModels;

    public $amount;

    public $fname;

    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($amount, $fname)
    {
        $this->amount = $amount;
        $this->fname = $fname;
        $this->subject = "Donation to Czar's Promise";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.donation')->text('mail.donation_plain');
    }
}
