<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products_tags')->truncate();

        $products = Product::take(10)->get();
        $categories = Category::take(10)->get();

        $productsTags = [];

        foreach ($products as $product) {

            $shuffledCategories = $categories->shuffle();
            $randomCategories = $shuffledCategories->random(rand(1, 3)); 
            
            foreach ($randomCategories as $category) {
                $productsTags[] = [
                    'product_id' => $product->id,
                    'category_id' => $category->id
                ];
            }
        }


        DB::table('products_tags')->insert($productsTags);
    }
}
