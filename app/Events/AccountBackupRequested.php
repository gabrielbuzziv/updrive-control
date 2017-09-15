<?php

namespace App\Events;

use App\Account;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;

class AccountBackupRequested implements ShouldQueue, ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * Account instance.
     *
     * @var
     */
    public $account;

    /**
     * Backup.
     *
     * @var
     */
    public $backup;

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
