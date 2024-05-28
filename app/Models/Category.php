<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Category Model
 */
class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'parent_id', 'created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = time();
            $model->updated_at = time();
        });

        static::updating(function ($model) {
            $model->updated_at = time();
        });
    }

    /**
     * Polymorphic relationship with Media
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * Relationship with Products through products_tags pivot table
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_tags', 'category_id', 'product_id');
    }

    /**
     * Relationship with parent Category
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relationship with child Categories
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
