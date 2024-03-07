<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string role_name
 * @property int created_at
 * @property int updated_at
 * @property int deleted_at
 */

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['role_name'];

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
     * Get the role for the blog user.
     */

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
