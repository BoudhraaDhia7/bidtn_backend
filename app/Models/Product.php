<?php

namespace App\Models;

use App\Traits\ApplyQueryScopes;
use App\Traits\PaginationParams;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Product Model
 */
class Product extends Model
{
    use ApplyQueryScopes, PaginationParams, HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'description', 'tag_id', 'user_id', 'auction_id', 'created_at', 'updated_at'
    ];

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

        static::deleting(function ($model) {
            $model->categories()->detach();
        });
    }

    /**
     * Scope to filter by product name
     */
    public function scopeFilterByName($query, $name = null)
    {
        if (!is_null($name)) {
            return $query->where('products.name', 'LIKE', "%{$name}%");
        }
        return $query;
    }

    /**
     * Scope to filter by category
     */
    public function scopeFilterByCategory($query, $category = null)
    {
        if (!is_null($category)) {
            return $query->where('categories.name', 'LIKE', "%{$category}%");
        }
        return $query;
    }

    /**
     * Scope to filter by status
     */
    public function scopeFilterByStatus($query, $status = null)
    {
        if (!is_null($status)) {
            return $query->where('products.status', 'LIKE', "%{$status}%");
        }
        return $query;
    }

    /**
     * Scope to filter by confirmation status
     */
    public function scopeFilterByIsConfirmed($query, $isConfirmed = null)
    {
        if (!is_null($isConfirmed)) {
            return $query->where('products.is_confirmed', $isConfirmed);
        }
        return $query;
    }

    /**
     * Polymorphic relationship with Media
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * Relationship with User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Auction
     */
    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    /**
     * Relationship with Category through products_tags pivot table
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products_tags', 'product_id', 'category_id');
    }
}
