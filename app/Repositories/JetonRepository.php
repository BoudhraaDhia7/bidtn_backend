<?php
namespace App\Repositories;

use Exception;
use App\Models\JetonPack;


class JetonRepository
{
    public static function createJetonPack($validated): array
    {   
        $attributesToUpdate = ['name', 'price', 'amount'];
        
        $filteredAttributes = array_intersect_key($validated, array_flip($attributesToUpdate));
    
        $jetonPack = JetonPack::create($filteredAttributes);
    
        if (!$jetonPack) {
            throw new Exception('Jeton pack creation failed');
        }
    
        return $jetonPack->toArray();
    }

    public static function getJetonPacks(): array
    {
        $jetonPacks = JetonPack::with('media')->get();
    
        // Check if there are any JetonPacks found
        if ($jetonPacks->isEmpty()) {
            throw new Exception('No jeton pack found', 404);
        }
    
        // Return the JetonPacks as an array, including their filtered media
        return $jetonPacks->toArray();
    }
    

    public static function getJetonPack($id): array
    {
        $jetonPack = JetonPack::find($id);
        if (!$jetonPack) {
            throw new Exception('Jeton pack not found', 404);
        }
        return $jetonPack->toArray();
    }

    public static function updateJetonPack($id, $validated): array
    {
        $jetonPack = JetonPack::find($id);
        if (!$jetonPack) {
            throw new Exception('Jeton pack not found', 404);
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
