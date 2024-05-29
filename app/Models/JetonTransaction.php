<?php

namespace App\Models;

use App\Traits\ApplyQueryScopes;
use App\Traits\PaginationParams;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JetonTransaction extends Model
{
    use HasFactory;
    use ApplyQueryScopes, PaginationParams;

    protected $fillable = ['user_id', 'jeton_pack_id','amount','transaction_type','created_at'];

    public $timestamps = false;
    
    protected $appends = ['user_code' , 'media'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = time();
        });
    }

    public function getUserCodeAttribute()
    {
        return "#" . $this->user->id . "_" . $this->user->first_name . $this->user->last_name;
    }

    /**
     * Define relation with user model
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function getMediaAttribute()
    {
        return $this->media()->first() ? $this->media()->first()->file_path : null;
    }
}
