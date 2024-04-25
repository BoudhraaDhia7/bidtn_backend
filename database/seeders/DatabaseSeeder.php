<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(AuctionTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(JetonTableSeeder::class);
        $this->call(ProductsTagsTableSeeder::class);
        $this->call(MediaTableSeeder::class);
    }
}
