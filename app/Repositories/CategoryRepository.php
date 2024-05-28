<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    public static function index()
    {
        $categories = Category::whereNull('parent_id')->get();

        $categories->map(function ($category) {
            $category->products_count = $category->products()->count();
            $category->name = __('messages.' . $category->name);

            $category->children = $category->children->map(function ($subCategory) {
                $subCategory->name = __('messages.' . $subCategory->name); 
                return $subCategory;
            });

            return [
                'id' => $category->id,
                'name' => $category->name,
                'children' => $category->children,
            ];
        });

        return $categories;
    }

    public static function getSubCategories(int $parentId): Collection
    {
        $subCategories = Category::where('parent_id', $parentId)->get();

        $subCategories->map(function ($subCategory) {
            $subCategory->name = trans('categories.' . $subCategory->name);
        });

        return $subCategories;
    }
}
