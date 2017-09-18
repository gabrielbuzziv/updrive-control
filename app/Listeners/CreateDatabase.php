<?php

namespace App\Listeners;


use App\Events\AccountCreated;
use App\Events\DatabaseCreated;
use App\Mail\AccountCreationFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CreateDatabase
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
     * @param  AccountCreated  $event
     * @return void
     */
    public function handle(AccountCreated $event)
    {
        try {
            DB::statement("CREATE DATABASE {$event->account->slug}");
            event(new DatabaseCreated($event->account));
        } catch (Exception $e) {
            Mail::to('updrive@updrive.me')->send(new AccountCreationFailed('Falhou ao criar o banco de dados'));
        }
    }
}
