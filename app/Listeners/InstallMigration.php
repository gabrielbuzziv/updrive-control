<?php

namespace App\Listeners;

use App\Events\DatabaseCreated;
use App\Events\MigrationInstalled;
use App\Mail\AccountCreationFailed;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class InstallMigration
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
     * @param  DatabaseCreated  $event
     * @return void
     */
    public function handle(DatabaseCreated $event)
    {
        try {
            Artisan::call('tenanti:install', ['driver' => 'account']);
            event(new MigrationInstalled($event->account));
        } catch (Exception $e) {
            Mail::to('updrive@updrive.me')->send(new AccountCreationFailed('Falhou ao instalar a migração'));
        }
    }
}
