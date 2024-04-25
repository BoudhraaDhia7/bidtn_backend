<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\Product;
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
        $user_id = 1; // Assuming all products belong to user with ID 1
        $auctions = Auction::all();

        foreach ($auctions as $auction) {
            for ($i = 1; $i <= 1; $i++) {
                Product::create([
                    'name' => "Product $i for Auction {$auction->id}",
                    'description' => "Description of Product $i in Auction {$auction->id}",
                    'user_id' => $user_id,
                    'auction_id' => $auction->id
                ]);
            }
        }
    }
}
