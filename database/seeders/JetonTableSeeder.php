<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JetonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing records
        DB::table('jeton_packs')->truncate();

        // Pack 1
        DB::table('jeton_packs')->insert([
            [
                'name' => 'Starter Pack',
                'price' => 12,
                'amount' => 10,
                'description' => 'This plan is for starting users. start with 10 jetons',
            ],
        ]);

        // Pack 2 gold pack
        DB::table('jeton_packs')->insert([
            [
                'name' => 'Silver Pack',
                'price' => 50,
                'amount' => 50,
                'description' => 'This plan is for silver users. start with 50 jetons',
            ],
        ]);

        // Pack 3 gold pack
        DB::table('jeton_packs')->insert([
            [
                'name' => 'Gold Pack',
                'price' => 95,
                'amount' => 100,
                'description' => 'This plan is for gold users. start with 100 jetons',
            ],
        ]);


    }
}
