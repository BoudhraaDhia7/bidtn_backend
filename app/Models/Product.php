<?php

namespace App\Models;

use App\Traits\ApplyQueryScopes;
use App\Traits\PaginationParams;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $ItemID
 * @property int $AuctionID
 * @property string     $Status
 * @property bool $IsConfirmed
 * @property string $name
 * @property string $description
 * @property string $category
 * @property int $user_id
 */
class Product extends Model
{
    use ApplyQueryScopes;

    use PaginationParams;

    use HasFactory;

    /**
     * Disable timestamps
     *
     * @var Date
     */
    public $timestamps = false;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description', 'tag_id', 'user_id', 'auction_id' ,'created_at', 'updated_at'];

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

    public function scopeFilterByName($query, $name = null)
    {  
        if (!is_null($name)) {
            return $query->where('name', 'LIKE', "%{$name}%");
        }

        return $query;
    }

    public function scopeFilterByCategory($query, $category = null)
    {
        if (!is_null($category)) {
            return $query->where('category', 'LIKE', "%{$category}%");
        }

        return $query;
    }

    public function scopeFilterByStatus($query, $status = null)
    {
        if (!is_null($status)) {
            return $query->where('status', 'LIKE', "%{$status}%");
        }

        return $query;
    }

    public function scopeFilterByIsConfirmed($query, $isConfirmed = null)
    {
        if (!is_null($isConfirmed)) {
            return $query->where('is_confirmed', $isConfirmed);
        }

        return $query;
    }
    
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products_tags', 'product_id', 'category_id');
    }
}
