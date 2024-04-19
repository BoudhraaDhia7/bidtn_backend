<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuctionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Example data array
       $auctions = [
        [
            'title' => 'Vintage Art Piece',
            'description' => 'A rare vintage painting from the 19th century.',
            'starting_price' => 5000,
            'is_finished' => false,
            'is_confirmed' => true,
            'user_id' => 1, 
            'start_date' => time(),
            'end_date' => time(),
        ],
        [
            'title' => 'Antique Vase',
            'description' => 'An exquisite antique vase from Asia.',
            'starting_price' => 1500,
            'is_finished' => false,
            'is_confirmed' => true,
            'user_id' => 1, 
            'start_date' => time(),
            'end_date' => time(),
        ],
    ];

    foreach ($auctions as $auction) {
        DB::table('auctions')->insert($auction);
    }
}
    }
}
