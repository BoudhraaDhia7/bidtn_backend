<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string model_type
 * @property int model_id
 * @property string file_name
 * @property string file_path
 * @property string file_type
 * @property \Illuminate\Support\Carbon|null deleted_at
 */
class Media extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $table = 'medias';

    protected $fillable = ['model_type', 'model_id', 'file_name', 'file_path', 'file_type', 'created_at', 'updated_at', 'deleted_at'];

    protected $dates = ['deleted_at'];

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

    /**
     * Get the owning model.
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
