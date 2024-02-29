<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing records
        DB::table('users')->truncate();

        DB::table('users')->insert([
            [
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'admin@test.com',
                'role_id' => 1, // 'Admin' role
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'user',
                'last_name' => 'user',
                'email' => 'user@test.com',
                'role_id' => 2, // 'User' role
                'password' => Hash::make('password'),
            ],
        ]);
        
    }
}
