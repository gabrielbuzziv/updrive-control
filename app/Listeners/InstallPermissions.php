<?php

namespace App\Listeners;

use App\Events\AccountCreated;
use App\Permission;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class InstallPermissions
{

    /**
     * Permissions array.
     *
     * @var array
     */
    protected $permissions = [];

    /**
     * InstallPermissions constructor.
     */
    public function __construct()
    {
        $this->permissions = [
            ['name' => 'manage-account', 'display_name' => 'Manage Account'],
            ['name' => 'manage-roles', 'display_name' => 'Manage Roles'],
            ['name' => 'manage-users', 'display_name' => 'Manage Users'],
            ['name' => 'manage-products', 'display_name' => 'Manage Products'],
            ['name' => 'manage-billings', 'display_name' => 'Manage Billings'],
        ];
    }

    /**
     * Handle the event.
     *
     * @param  AccountCreated $event
     * @return void
     */
    public function handle(AccountCreated $event)
    {
        // Set the account database to connect in the current account database.
        config(['database.connections.account.database' => $event->account->slug]);

        // Connect to account database and permission table.
        $query = DB::connection('account')->table('permissions');

        // Insert all permissions
        array_walk($this->permissions, function ($permission) use ($query) {
            $query->insert($permission);
        });
    }
}
