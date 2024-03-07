<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JetonPack extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'amount', 'created_at', 'updated_at', 'deleted_at'];

    public $timestamps = false;

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

    /*
    * Define the relation with the transaction table
    **/
    public function transactions() : HasMany
    {
        return $this->hasMany(JetonTransaction::class, 'jeton_pack_id');
    }
}
