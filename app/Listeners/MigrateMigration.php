<?php

namespace App\Listeners;

use App\Events\MigrationInstalled;
use App\Events\MigrationMigrated;
use App\Mail\AccountCreationFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class MigrateMigration
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MigrationInstalled  $event
     * @return void
     */
    public function handle(MigrationInstalled $event)
    {
        try {
            Artisan::call('tenanti:migrate', ['driver' => 'account', '--force']);
            event(new MigrationMigrated($event->account));
        } catch (Exception $e) {
            Mail::to('updrive@updrive.me')->send(new AccountCreationFailed('Falhou ao migrar a migração'));
        }
    }
}
