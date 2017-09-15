<?php

use Illuminate\Database\Seeder;
use App\User;

class InstalationSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! User::find(1)) {
            $user = User::create([
                'name'     => 'Administrador',
                'email'    => 'gabrielbuzziv@gmail.com',
                'password' => 'password',
            ]);

            $user->roles()->attach(1);
        }
    }
}
