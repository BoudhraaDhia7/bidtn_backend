<?php

namespace Database\Seeders;

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
        DB::table('medias')->insert([
            'model_type'=>'App\Models\Product',
            'model_id'=> '1',
            'file_name'=>'test',
            'file_path'=>'https://picsum.photos/200',
        ]);

        DB::table('medias')->insert([
            'model_type'=>'App\Models\Product',
            'model_id'=> '1',
            'file_name'=>'test',
            'file_path'=>'https://picsum.photos/200',
        ]);

        DB::table('medias')->insert([
            'model_type'=>'App\Models\Product',
            'model_id'=> '1',
            'file_name'=>'test',
            'file_path'=>'https://picsum.photos/200',
        ]);
    }
}
