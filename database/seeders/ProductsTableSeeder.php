<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing records
        // for ($i = 1; $i <= 15; $i++) {
        //     DB::table('products')->insert([
        //         [
        //             'name' => 'Item' . $i,
        //             'description' => 'description' . $i,
        //             'user_id' => 30,
        //         ],
        //     ]);
        // }
        DB::table('products')->insert([
            'name'=>'test',
            'description' => 'description' ,
            'user_id' => 1,        
        ]);
    }
}
