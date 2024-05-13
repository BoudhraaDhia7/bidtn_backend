<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = 1;
        Product::create([
            'name' => 'OPPO A18',
            'description' => 'OPPO A18 RAM 8(4+4) ROM 128 garantie 2ans',
            'user_id' => $user_id,
            'auction_id' => 1,
        ]);

        Product::create([
            'name' => 'Redmi Note 13',
            'description' => "Avendre Redmi Note 13❤️‍🔥
            Cacheté pâquet fermé 🎁
            Validé Sur Sajalni ✅️
            Processeur SnapDragon 685G🐲
            Caméra 108mp 📸
            6/128 ===> prix 630D
            8/128 ===> prix 680D
            8/256 ===> prix 730D
            Livraison sur toute La tunisie 🇹🇳",
            'user_id' => $user_id,
            'auction_id' => 2,
        ]);

        Product::create([
            'name' => 'Ps4 Pro',
            'description' => 'Ps4 Pro 1to version God of war comme neuf peu servi jamais réparé aucun défaut avec 2 manettes cd cyberpunk 2077 et god of war ragnarok et diablo IV ',
            'user_id' => $user_id,
            'auction_id' => 3,
        ]);

        Product::create([
            'name' => 'Dell optiplex Intel core i5 /8GB /256ssd',
            'description' => 'Dell optiplex Intel core i5 /8GB
            💖🔥Intel Core i5 @3340 , 3.30ghz
            💖🔥Génération : 3ém génération
            💖🔥Fréquence processeurs 3.40ghz
            💖🔥Ram 8GB DDR3
            💖🔥Disque dur 256GB SSD
            💖🔥Carte graphique Intel HD 2500
            💖🔥Windows 10 Pro 64 bits
            💖🔥Garantie 3 mois',
            'user_id' => $user_id,
            'auction_id' => 4,
        ]);
    }
}
