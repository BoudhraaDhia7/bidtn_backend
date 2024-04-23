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
        return $this->hasOne(Product::class, 'AuctionID');
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
            $query->where('ProductName', 'like', '%' . $keyword . '%');
        }
        return $query;
    }

}
