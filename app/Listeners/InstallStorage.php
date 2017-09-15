<?php

namespace App\Listeners;

use App\Events\MigrationMigrated;
use App\Events\StorageInstalled;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InstallStorage
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MigrationMigrated  $event
     * @return void
     */
    public function handle(MigrationMigrated $event)
    {
        try {
            Storage::disk('s3')->makeDirectory($event->account->slug);
            Storage::disk('s3')->makeDirectory("{$event->account->slug}/database");
            Storage::disk('s3')->makeDirectory("{$event->account->slug}/documents");
            event(new StorageInstalled($event->account));
        } catch (Exception $e) {
            Mail::to('updrive@updrive.me')->send(new AccountCreationFailed('Falhou ao instalar Storage'));
        }
    }
}
