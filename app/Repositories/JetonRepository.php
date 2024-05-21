<?php
namespace App\Repositories;

use App\Exceptions\GlobalException;
use App\Models\JetonPack;

class JetonRepository
{
    /**
     * Create a new jeton pack record in the database.
     *
     * @param array $validated Validated data from the request
     * @return array
     * @throws Exception if the jeton pack creation fails
     */
    public static function createJetonPack($name, $description, $price, $amount): void
    {
        JetonPack::create([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'amount' => $amount,
        ]);
    }

    /**
     * Get all jeton packs from the database.
     * @return array
     */
    public static function getJetonPacks(): array
    {
        $jetonPacks = JetonPack::with('media')->get();

        if ($jetonPacks->isEmpty()) {
            throw new GlobalException('jeton_pack_not_found', 404);
        }

        return $jetonPacks->toArray();
    }

    /**
     * Get a jeton pack from the database.
     * @param int $id
     * @return array
     */
    public static function getJetonPack($id): array
    {
        $jetonPack = JetonPack::find($id);
        if (!$jetonPack) {
            throw new GlobalException('jeton_pack_not_found', 404);
        }
        return $jetonPack->toArray();
    }

    /**
     * Update a jeton pack record in the database.
     *
     * @param int $id
     * @param array $validated Validated data from the request
     * @return array
     * @throws Exception if the jeton pack update fails
     */
    public static function updateJetonPack($name, $description, $price, $amount, $jetonPack): void
    {   
        $jetonPack->name = $name;
        $jetonPack->description = $description;
        $jetonPack->price = $price;
        $jetonPack->amount = $amount;
        $jetonPack->save();
    }

    public static function deleteJetonPack($jetonPack): void
    {
        $jetonPack->delete();
    }
}
