<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name'         => 'master',
                'display_name' => 'Administrador',
                'permissions' => [
                    'manage-permissions', 'manage-users',
                    'manage-roles', 'manage-accounts', 'manage-backups',
                    'manage-settings'
                ]
            ],
        ];

        array_walk($roles, function ($role) {
            $permissions = $role['permissions'];
            unset($role['permissions']);
            $role = Role::create($role);

            array_walk($permissions, function ($permission) use ($role) {
                $permission = Permission::where('name', $permission)->first();
                $role->perms()->attach($permission->id);
            });
        });
    }
}
