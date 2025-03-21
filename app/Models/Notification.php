<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'title', 'description', 'icon', 'read_at'];
    
    protected $dateFormat = 'U';

    protected $appends = ['read'];

    public function getReadAttribute()
    {
        return true;    
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
