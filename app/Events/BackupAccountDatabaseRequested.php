<?php

namespace App\Events;

use App\Account;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;

class BackupAccountDatabaseRequested
{
    use InteractsWithSockets, SerializesModels;

    /**
     * Account instance.
     *
     * @var
     */
    public $account;

    /**
     * BackupAccountDatabaseRequested constructor.
     *
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
