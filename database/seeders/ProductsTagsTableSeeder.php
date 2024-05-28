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

        // Map of product names to their categories
        $productCategories = [
            'OPPO A18' => ['Téléphones', 'Informatique et Multimedias'],
            'Redmi Note 13' => ['Téléphones', 'Informatique et Multimedias'],
            'Ps4 Pro' => ['Jeux vidéo et Consoles', 'Informatique et Multimedias'],
            'Dell optiplex Intel core i5 /8GB /256ssd' => ['Ordinateurs portables', 'Informatique et Multimedias'],
        ];

        $productsTags = [];

        foreach ($productCategories as $productName => $categories) {
            $product = Product::where('name', $productName)->first();
            if ($product) {
                foreach ($categories as $categoryName) {
                    $category = Category::where('name', $categoryName)->first();
                    if ($category) {
                        // Add the child category
                        if (!DB::table('products_tags')->where('product_id', $product->id)->where('category_id', $category->id)->exists()) {
                            $productsTags[] = [
                                'product_id' => $product->id,
                                'category_id' => $category->id,
                            ];
                        }

                     
                    }
                }
            }
        }

        DB::table('products_tags')->insert($productsTags);
    }
}
