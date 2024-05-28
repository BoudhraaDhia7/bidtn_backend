<?php
namespace Database\Seeders;

use App\Models\Category;
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
            [
                'name' => 'Véhicules',
                'sub_categories' => [
                    'Motos',
                    'Voitures',
                    'Remorques et Caravanes',
                    'Engins Agricoles',
                    'Autres Véhicules',
                    'Engins BTP',
                    'Bateaux',
                    'Pièces et Accessoires pour véhicules',
                    'Camions',
                ]
            ],
            [
                'name' => 'Pour la Maison et Jardin',
                'sub_categories' => [
                    'Jardins et Outils de bricolage',
                    'Electroménagers et Vaisselles',
                    'Meubles et Décoration',
                ]
            ],
            [
                'name' => 'Immobilier',
                'sub_categories' => [
                    'Colocations',
                    'Maisons et Villas',
                    'Magasins, Commerces et Locaux industriels',
                    'Locations de vacances',
                    'Appartements',
                    'Bureaux et Plateaux',
                    'Autres Immobiliers',
                    'Terrains et Fermes',
                ]
            ],
            [
                'name' => 'Entreprises',
                'sub_categories' => [
                    'Matériels Professionnels',
                    'Business et Affaires commerciales',
                    'Stocks et Vente en gros',
                ]
            ],
            [
                'name' => 'Habillement et Bien Etre',
                'sub_categories' => [
                    'Equipements pour enfant et bébé',
                    'Montres et Bijoux',
                    'Produits de beauté',
                    'Vêtements',
                    'Sacs et Accessoires',
                    'Chaussures',
                    'Vêtements pour enfant et bébé',
                ]
            ],
            [
                'name' => 'Informatique et Multimedias',
                'sub_categories' => [
                    'Image & Son',
                    'Ordinateurs portables',
                    'Appareils photo et Caméras',
                    'Télévisions',
                    'Téléphones',
                    'Accessoires informatiques et Gadgets',
                    'Jeux vidéo et Consoles',
                    'Tablettes',
                ]
            ],
            [
                'name' => 'Loisirs et Divertissement',
                'sub_categories' => [
                    'Sports et Loisirs',
                    'Arts et Collections',
                    'Animaux',
                    'Films, Livres, Magazines',
                    'Vélos',
                    'Instruments de musique',
                    'Voyages et Billetteries',
                ]
            ],
            [
                'name' => 'Vacances',
                'sub_categories' => [
                    'Vacances',
                ]
            ],
            [
                'name' => 'Autres',
                'sub_categories' => [
                    'Autres',
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create(['name' => $categoryData['name']]);

            foreach ($categoryData['sub_categories'] as $subCategoryName) {
                Category::create([
                    'name' => $subCategoryName,
                    'parent_id' => $category->id
                ]);
            }
        }
    }
}
