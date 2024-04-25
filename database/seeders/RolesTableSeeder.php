<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the roles data
        $roles = [
            [
                'role_name' => 'Admin',
            ],
            [
                'role_name' => 'User',
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
