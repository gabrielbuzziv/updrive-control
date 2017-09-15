<?php

namespace App\Events;

use App\Account;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DatabaseBackupDone implements ShouldQueue, ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * Account.
     *
     * @var
     */
    public $account;

    /**
     * Database backup.
     *
     * @var
     */
    public $backup;

    /**
     * DatabaseBackupDone constructor.
     * @param Account $account
     * @param $backup
     */
    public function __construct(Account $account, $backup)
    {
        $this->account = $account;
        $this->backup = $backup;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('account');
    }
}
