<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JetonTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'jeton_pack_id','amount','created_at'];

    public $timestamps = false;
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = time();
        });
    }

    /**
     * Define relation with user model
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
