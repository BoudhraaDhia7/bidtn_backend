<?php

namespace App\Models;

use App\Traits\ApplyQueryScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ProductsTag extends Model
{
    use ApplyQueryScopes;

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
    protected $fillable = ['created_at', 'updated_at'];

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
   
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category_tags', 'category_id', 'product_id');
    }

}
