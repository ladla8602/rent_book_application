<?php

use Illuminate\Database\Seeder;
use App\Role;
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
    }
}
