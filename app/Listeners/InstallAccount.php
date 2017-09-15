<?php

namespace App\Listeners;

use App\Events\AccountCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InstallAccount implements ShouldQueue
{

    /**
     * The account that is being installed.
     *
     * @var
     */
    protected $account;

    /**
     * The connection that will be used to communicate with account database.
     *
     * @var
     */
    protected $connection;

    /**
     * InstallAccount constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AccountCreated $event
     * @return void
     */
    public function handle(AccountCreated $event)
    {
        $this->account = $event->account;

        try {
            $this->installSettings();
            $this->createDatabase();
            $this->createStorage();
            $this->connect();
            $this->installPermissions();
            $this->installRoles();
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
        $this->addSetting('storage_limit', 1024);
        $this->addSetting('storage_listable', 0);
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
     * Create account database.
     */
    private function createDatabase()
    {
        DB::statement("CREATE DATABASE {$this->account->slug}");
        Artisan::call('tenanti:install', ['driver' => 'account']);
        Artisan::call('tenanti:migrate', ['driver' => 'account']);
    }

    /**
     * Create account storage.
     */
    private function createStorage()
    {
        Storage::disk('s3')->makeDirectory($this->account->slug);
        Storage::disk('s3')->makeDirectory("{$this->account->slug}/database");
        Storage::disk('s3')->makeDirectory("{$this->account->slug}/documents");
        Storage::disk('s3')->makeDirectory("{$this->account->slug}/images");
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
            ['name' => 'manage-account', 'display_name' => 'Manage Account'],
            ['name' => 'manage-roles', 'display_name' => 'Manage Roles'],
            ['name' => 'manage-users', 'display_name' => 'Manage Users'],
            ['name' => 'manage-products', 'display_name' => 'Manage Products'],
            ['name' => 'manage-billings', 'display_name' => 'Manage Billings'],
        ];

        array_walk($permissions, function ($permission) {
            $this->connection->table('permissions')->insert($permission);
        });
    }

    /**
     * Install roles in account database.
     */
    private function installRoles()
    {
        $roles = [
            ['name' => 'owner', 'display_name' => 'Owner', 'default' => true, 'permissions' => ['manage-account']],
            ['name' => 'administration', 'display_name' => 'Administration', 'default' => true, 'permissions' => ['manage-roles', 'manage-users']],
            ['name' => 'manager', 'display_name' => 'Manager', 'default' => true, 'permissions' => ['manage-products']],
        ];

        array_walk($roles, function ($role) {
            $permissions = $role['permissions'];
            $role = array_filter($role, function ($key) {
                return $key != 'permissions';
            }, ARRAY_FILTER_USE_KEY);
            $roleId = $this->connection->table('roles')->insertGetId($role);

            array_walk($permissions, function ($permission) use ($roleId) {
                $permissionId = $this->connection->table('permissions')->select('id')->where('name', $permission)->first()->id;
                $this->connection->table('permission_role')->insert(['role_id' => $roleId, 'permission_id' => $permissionId]);
            });
        });
    }

    /**
     * Create admin in account database and notify with an email.
     */
    private function createAdmin()
    {
        $password = str_random(8);
        $userId = $this->connection->table('users')->insertGetId([
            'name'     => $this->account->name,
            'email'    => $this->account->email,
            'password' => bcrypt($password),
        ]);

        $roles = $this->connection->table('roles')->get()->toArray();
        array_walk($roles, function ($role) use ($userId) {
            $query = $this->connection->table('role_user');
            $query->insert(['user_id' => $userId, 'role_id' => $role->id]);
        });

        Mail::to($this->account->email)->send(new \App\Mail\AccountCreated($this->account, $password));
    }
}
