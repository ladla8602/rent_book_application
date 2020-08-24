<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::insert(
            [
                [
                    'role' => 'admin',
                    'hierarchy' => 1
                ],
                [
                    'role' => 'renter',
                    'hierarchy' => 2
                ]
            ]

        );
        User::insert(
            [
                [
                    'name' => 'admin',
                    'username' => 'admin',
                    'email' => 'admin@yopmail.com',
                    'password' => bcrypt('admin123'),
                    'role' => 1
                ],
                [
                    'name' => 'renter',
                    'username' => 'renter',
                    'email' => 'renter@yopmail.com',
                    'password' => bcrypt('renter123'),
                    'role' => 2
                ]
            ]

        );
    }
}
