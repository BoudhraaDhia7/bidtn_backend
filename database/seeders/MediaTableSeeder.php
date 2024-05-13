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
        Media::create([
            'model_id' => 1,
            'model_type' => 'App\Models\Product',
            'file_type' => 'image/webp',
            'file_path' => 'https://i.ibb.co/mbZs7gD/Oppo1.webp',
        ]);

        Media::create([
            'model_id' => 2,
            'model_type' => 'App\Models\Product',
            'file_type' => 'image/webp',
            'file_path' => 'https://i.ibb.co/b5d7X8c/xiaomi1.webp',
        ]);

        Media::create([
            'model_id' => 2,
            'model_type' => 'App\Models\Product',
            'file_type' => 'image/webp',
            'file_path' => 'https://i.ibb.co/1T5JSbM/xiami2.webp',
        ]);

        Media::create([
            'model_id' => 2,
            'model_type' => 'App\Models\Product',
            'file_type' => 'image/webp',
            'file_path' => 'https://i.ibb.co/CWvXL2f/xiaomi3-webp.webp',
        ]);

        Media::create([
            'model_id' => 3,
            'model_type' => 'App\Models\Product',
            'file_type' => 'image/webp',
            'file_path' => 'https://i.ibb.co/FxDF6HM/ps41.webp',
        ]);

        Media::create([
            'model_id' => 3,
            'model_type' => 'App\Models\Product',
            'file_type' => 'image/webp',
            'file_path' => 'https://i.ibb.co/QkYVnP9/ps42.webp',
        ]);

        Media::create([
            'model_id' => 4,
            'model_type' => 'App\Models\Product',
            'file_type' => 'image/webp',
            'file_path' => 'https://i.ibb.co/yQGcb3W/dell1.webp',
        ]);

        Media::create([
            'model_id' => 4,
            'model_type' => 'App\Models\Product',
            'file_type' => 'image/webp',
            'file_path' => 'https://i.ibb.co/4j023tg/dell2.webp',
        ]);

     
    }

}
