<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Electronics',
            'Fashion',
            'Home & Garden',
            'Sporting Goods',
            'Toys & Hobbies',
            'Business & Industrial',
            'Health & Beauty',
            'Music',
            'Books',
            'Collectibles',
            'Crafts',
            'Pet Supplies',
            'Cameras & Photo',
            'Cell Phones & Accessories',
            'Computers & Networking',
            'Consumer Electronics',
            'Jewelry & Watches',
            'Travel',
            'Video Games & Consoles',
            'Everything Else',
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create([
                'name' => $category,
            ]);
        }
    }
}
