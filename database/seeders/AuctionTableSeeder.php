<?php
namespace Database\Seeders;

use App\Models\Auction;
use App\Models\Product;
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
        $user_id = 1;

        for ($i = 1; $i <= 10; $i++) {
            Auction::create([
                'title' => "Auction Title $i",
                'description' => "Description for Auction $i",
                'starting_price' => rand(100, 500),
                'user_id' => $user_id,
                'end_date' => time() + 86400 * 30,
            ]);
        }
    
    }
}
