<?php

namespace App\Listeners;

use App\Events\StorageInstalled;
use App\Mail\AccountCreated;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InstallAccount
{

    protected $account;
    protected $connection;

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
     * @param  StorageInstalled $event
     * @return void
     */
    public function handle(StorageInstalled $event)
    {
        $this->account = $event->account;

        try {
            $this->installSettings();
            $this->connect();
            $this->installPermissions();
            $this->createAdmin();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Install account default settings.
     */
    private function installSettings()
    {
        $this->addSetting('companies_limit', 20);
        $this->addSetting('backup_database_limit', 7);
    }

    /**
     * Custom create setting method.
     *
     * @param $label
     * @param $value
     */
    private function addSetting($label, $value)
    {
        $this->account->settings()->create(['label' => $label, 'value' => $value]);
    }

    /**
     * Connect to the brand new database.
     */
    private function connect()
    {
        config()->set('database.connections.current_account', [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', '127.0.0.1'),
            'port'      => env('DB_PORT', '3306'),
            'database'  => $this->account->slug,
            'username'  => env('DB_USERNAME', 'forge'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
        ]);

        $this->connection = DB::reconnect('current_account');
    }

    /**
     * Install permissions in account database.
     */
    private function installPermissions()
    {
        $permissions = [
            ['name' => 'manage-users', 'display_name' => 'Gerenciar Usuários'],
            ['name' => 'manage-companies', 'display_name' => 'Gerenciar Empresas'],
            ['name' => 'manage-contacts', 'display_name' => 'Gerenciar Contatos'],
            ['name' => 'manage-updrive', 'display_name' => 'Gerenciar Documentos'],
        ];

        array_walk($permissions, function ($permission) {
            $this->connection->table('permissions')->insert($permission);
        });
    }

    /**
     * Create admin in account database and notify with an email.
     */
    private function createAdmin()
    {
        $password = str_random(8);
        $userId = $this->connection->table('users')->insertGetId([
            'name'      => $this->account->name,
            'email'     => $this->account->email,
            'password'  => bcrypt($password),
            'is_user'   => true,
        ]);

        $this->connection->table('users_registration')->insert([
            'email' => $this->account->email,
            'token' => md5($this->account->email),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $permissions = $this->connection->table('permissions')->get()->toArray();
        array_walk($permissions, function ($permission) use ($userId) {
            $query = $this->connection->table('permission_user');
            $query->insert(['user_id' => $userId, 'permission_id' => $permission->id]);
        });

        Mail::to($this->account->email)->send(new AccountCreated($this->account));
    }
}
