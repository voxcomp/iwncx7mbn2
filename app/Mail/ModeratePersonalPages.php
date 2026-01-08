<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ModeratePersonalPages extends Mailable
{
    use Queueable, SerializesModels;

	public $count;
	public $link;
	public $subject;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($count)
    {
        $this->count = $count;
        $this->link = config('app.url').'/admin/personalpage';
        $this->subject = 'Personal Pages For Review';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.personalpageAdmin');
    }
}
