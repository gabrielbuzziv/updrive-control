<?php

namespace App\Listeners;

use App\Events\AccountCreated;
use App\Mail\AccountCreated as AccountCreatedMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CreateAdmin
{

    /**
     * Database connection.
     *
     * @var
     */
    protected $connection;

    /**
     * CreateAdmin constructor.
     */
    public function __construct()
    {
        $this->connection = DB::connection('account');
    }

    /**
     * Handle the event.
     *
     * @param  AccountCreated  $event
     * @return void
     */
    public function handle(AccountCreated $event)
    {
        $account = $event->account;
        config(['database.connections.account.database' => $account->slug]);

        $query = $this->connection->table('users');

        $password = str_random(8);

        $userId = $query->insertGetId([
            'name' => $account->name,
            'email' => $account->email,
            'password' => bcrypt($password)
        ]);

        $roles = $this->connection->table('roles')->get()->toArray();
        array_walk($roles, function ($role) use ($userId) {
            $query = $this->connection->table('role_user');
            $query->insert(['user_id' => $userId, 'role_id' => $role->id]);
        });

        Mail::to($account->email)->send(new AccountCreatedMail($account, $password));
    }
}
