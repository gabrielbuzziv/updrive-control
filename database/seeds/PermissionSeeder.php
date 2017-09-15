<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name'         => 'manage-permissions',
                'display_name' => 'Manage permissions',
            ],
            [
                'name'         => 'manage-roles',
                'display_name' => 'Manage Roles',
            ],
            [
                'name'         => 'manage-users',
                'display_name' => 'Manage Users',
            ],
            [
                'name'         => 'manage-settings',
                'display_name' => 'Manage Settings',
            ],
            [
                'name'         => 'manage-accounts',
                'display_name' => 'Manage Accounts',
            ],
            [
                'name' => 'manage-backups',
                'display_name' => 'Manage Backups',
            ]
        ];
        
        array_walk($permissions, function ($permission) {
            Permission::create($permission);
        });
    }
}
