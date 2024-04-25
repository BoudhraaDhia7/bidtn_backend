<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository
{   
    /**
     * Get all categories from the database.
     *
     * @return Collection
     */
    public static function index(): Collection
    {
        $categories = Category::all();
        
        $categories->map(function ($category) {
            $category->products_count = $category->products()->count();
        });
        return $categories;
    }
}
