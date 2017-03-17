<?php

namespace App\Mail;

use App\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Account instance.
     *
     * @var
     */
    protected $account;

    /**
     * Password.
     *
     * @var
     */
    protected $password;

    /**
     * AccountCreated constructor.
     *
     * @param Account $account
     */
    public function __construct(Account $account, $password)
    {
        $this->account = $account;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.account.created')
                    ->with([
                        'account' => $this->account,
                        'password' => $this->password
                    ]);
    }
}
