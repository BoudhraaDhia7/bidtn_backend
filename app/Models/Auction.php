<?php

namespace App\Models;

use App\Models\Product;
use App\Traits\ApplyQueryScopes;
use App\Traits\PaginationParams;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// TODO: Refactor the model
class Auction extends Model
{
    use HasFactory;
    
    use ApplyQueryScopes, PaginationParams;


    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'starting_price',
        'is_finished',
        'starting_user_number',
        'is_confirmed',
        'user_id',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
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
    }
    
    public function Product(): HasOne
    {
        return $this->hasOne(Product::class, 'auction_id');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    //relationship with user
    //TODO -  Add miore fillter
    public function scopeFilterByKeyword($query, $keyword)
    {
        if ($keyword !== null) {
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('title', 'like', '%' . $keyword . '%')
                         ->orWhere('description', 'like', '%' . $keyword . '%')
                         ->orWhere('starting_price', 'like', '%' . $keyword . '%')
                         ->orWhere('is_finished', 'like', '%' . $keyword . '%')
                         ->orWhere('starting_user_number', 'like', '%' . $keyword . '%')
                         ->orWhereHas('product.categories', function ($query) use ($keyword) {
                             $query->where('name', 'like', '%' . $keyword . '%');
                         });
            });
        }
        return $query;
    }
    

    public function scopeFilterByCategory($query, $category)
    {
        if ($category !== null) {
            $query->whereHas('product.categories', function ($query) use ($category) {
                $query->whereIn('name', $category);
            });
        }
        return $query;
    }

}
