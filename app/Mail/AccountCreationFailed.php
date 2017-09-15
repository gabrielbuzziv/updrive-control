<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountCreationFailed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var $fail
     */
    public $fail;

    /**
     * Create a new message instance.
     */
    public function __construct($fail)
    {
        $this->fail = $fail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.fail');
    }
}
