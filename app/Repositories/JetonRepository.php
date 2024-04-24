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
    public static function createJetonPack($validated): array
    {   
        $attributesToUpdate = ['name', 'price', 'amount'];
        
        $filteredAttributes = array_intersect_key($validated, array_flip($attributesToUpdate));
    
        $jetonPack = JetonPack::create($filteredAttributes);
    
        if (!$jetonPack) {
            throw new GlobalException('jeton_pack_creation_failed');
        }
    
        return $jetonPack->toArray();
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
    public static function updateJetonPack($id, $validated): array
    {
        $jetonPack = JetonPack::find($id);
        if (!$jetonPack) {
            throw new GlobalException('jeton_pack_not_found', 404);
        }
        $attributesToUpdate = ['name', 'price', 'amount'];
        foreach ($attributesToUpdate as $attribute) {
            if (isset($validated[$attribute])) {
                $jetonPack->{$attribute} = $validated[$attribute];
            }
        }
        $jetonPack->save();
        return $jetonPack->toArray();
    }
}
