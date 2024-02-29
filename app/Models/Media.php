<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $table = 'medias';


    protected $fillable = ['model_type', 'model_id', 'file_name', 'file_path', 'file_type'];

    /**
     * Get the owning model.
     */
    public function model() : MorphTo
    {
        return $this->morphTo();
    }
}
