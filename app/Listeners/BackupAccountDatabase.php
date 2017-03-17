<?php

namespace App\Listeners;

use App\Account;
use App\Events\BackupAccountDatabaseRequested;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BackupAccountDatabase
{

    /**
     * Create the event listener
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BackupAccountDatabaseRequested  $event
     * @return void
     */
    public function handle(BackupAccountDatabaseRequested $event)
    {
//        sleep(60);
        $account = $event->account;
        $database = $account->slug;
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        $filename = $database . '-' . Carbon::now()->format('YmdHis') . '.sql';
        $path = storage_path("app/accounts/{$database}/database/{$filename}");

        @exec("mysqldump {$database} -u {$username} -p'{$password}' >{$path}");
    }
}
