<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountValidation extends Mailable
{
    use Queueable, SerializesModels;

    public $link;

    public $title = 'Account Validation';

    public $subject = "Czar's Promise Account Validation";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link)
    {
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->view('mail.validateaccount')->text('mail.validateaccount_plain');
    }
}
