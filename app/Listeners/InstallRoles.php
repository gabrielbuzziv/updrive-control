<?php

namespace App\Listeners;

use App\Events\AccountCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class InstallRoles
{

    /**
     * Database connection.
     *
     * @var
     */
    protected $connection;

    /**
     * Roles array
     *
     * @var array
     */
    protected $roles = [];

    /**
     * InstallRoles constructor.
     */
    public function __construct()
    {
        $this->connection = DB::connection('account');

        $this->roles = [
            ['name' => 'owner', 'display_name' => 'Owner', 'default' => true, 'permissions' => ['manage-account']],
            ['name' => 'administration', 'display_name' => 'Administration', 'default'=> true, 'permissions' => ['manage-roles', 'manage-users']],
            ['name' => 'manager', 'display_name' => 'Manager', 'default'=> true, 'permissions' => ['manage-products']],
        ];
    }

    /**
     * Handle the event.
     *
     * @param  AccountCreated  $event
     * @return void
     */
    public function handle(AccountCreated $event)
    {
        // Set the account database to connect in the current account database.
        config(['database.connections.account.database' => $event->account->slug]);

        // Connect to account database and permission table.
        $query = $this->connection->table('roles');

        // Insert all permissions
        array_walk($this->roles, function ($role) use ($query) {
            $permissions = $role['permissions'];
            $role = array_filter($role, function ($key) { return $key != 'permissions'; }, ARRAY_FILTER_USE_KEY);
            $roleId = $query->insertGetId($role);

            $query = $this->connection->table('permission_role');

            array_walk($permissions, function ($permission) use ($query, $roleId) {
                $permission = $this->connection->table('permissions')->select('id')->where('name', $permission)->first();
                $query->insert(['role_id' => $roleId, 'permission_id' => $permission->id]);
            });
        });
    }
}
