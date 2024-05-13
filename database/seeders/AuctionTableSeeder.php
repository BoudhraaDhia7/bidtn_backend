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
        Auction::create([
            'title' => 'OPPO A18',
            'description' => 'OPP A18 Écran: 6.7" Amoled 120Hz (1080 x 2412pixels) AGC DT-Star2 - Système d\'exploitation: ColorOS 13.1 - RAM: 8 Go - Stockage: 256 Go - Connectivité: Wifi 5G et Bluetooth 5.3 - Batterie: 5000mAh - Charge rapide 67W - NFC - Lecteur d\'empreinte digitale, Reconnaissance faciale',
            'starting_price' => 350,
            'user_id' => 1,
            'end_date' => time() + 86400 * 30,
            'start_date' => time() + 120,
            'starting_user_number' => 1,
            'is_confirmed' => true,
        ]);

        Auction::create([
            'title' => 'Redmi Note 13',
            'description' => "Avendre Redmi Note 13❤️‍🔥
            Cacheté pâquet fermé 🎁
            Validé Sur Sajalni ✅️
            Processeur SnapDragon 685G🐲
            Caméra 108mp 📸
            6/128 ===> prix 630D
            8/128 ===> prix 680D
            8/256 ===> prix 730D
            Livraison sur toute La tunisie 🇹🇳",
            'starting_price' => 400,
            'user_id' => 1,
            'end_date' => time() + 86400 * 30,
            'start_date' => time() + 180,
            'starting_user_number' => 1,
            'is_confirmed' => true,
        ]);

        Auction::create([
            'title' => 'Ps4 Pro',
            'description' => 'Ps4 Pro 1to version God of war comme neuf peu servi jamais réparé aucun défaut avec 2 manettes cd cyberpunk 2077 et god of war ragnarok et diablo IV ',
            'starting_price' => 600,
            'user_id' => 1,
            'end_date' => time() + 86400 * 30,
            'start_date' => time() + 60,
            'starting_user_number' => 1,
            'is_confirmed' => true,
        ]);

        Auction::create([
            'title' => 'Dell optiplex Intel core i5 /8GB /256ssd',
            'description' => 'Dell optiplex Intel core i5 /8GB
            💖🔥Intel Core i5 @3340 , 3.30ghz
            💖🔥Génération : 3ém génération
            💖🔥Fréquence processeurs 3.40ghz
            💖🔥Ram 8Gb-DDR3
            💖🔥Disque 256SSD Windows installer 64 bite
            💖🔥USB/DVD/DISPAY
            💖🔥Livraison disponible a tt Tunisie
            💖🔥Prix Unité seulement : 440dt',
            'starting_price' => 250,
            'user_id' => 1,
            'end_date' => time() + 86400 * 30,
            'start_date' => time() + 60,
            'starting_user_number' => 1,
            'is_confirmed' => true,
        ]);
    }
}
