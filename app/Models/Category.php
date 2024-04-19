<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $name
 */
class Category extends Model
{

    use HasFactory;

    /**
     * Disable timestamps
     *
     * @var Date
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'created_at', 'updated_at'];


    /**
     * The "booting" method of the model.
     *
     * @return void
     */

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
    
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }
     
    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_tags', 'category_id', 'product_id');
    }
}
