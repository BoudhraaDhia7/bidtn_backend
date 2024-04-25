<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();
        $base_url = 'https://picsum.photos/200/300?random=';

        foreach ($products as $product) {
            for ($j = 1; $j <= 2; $j++) {
                Media::create([
                    'model_type' => 'App\Models\Product',
                    'model_id' => $product->id,
                    'file_name' => "Image_{$j}_Product_{$product->id}.jpg",
                    'file_path' => "{$base_url}{$product->id}$j", 
                    'file_type' => 'image/jpeg'
                ]);
            }
        }
    }

}
