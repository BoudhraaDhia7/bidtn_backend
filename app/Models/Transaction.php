<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'auction_id', 'amount', 'type'];
    
    protected $dateFormat = 'U';
    
    protected $appends = ['bidder_name'];

    public function getBidderNameAttribute()
    {
        return $this->user->first_name . ' ' . $this->user->last_name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
}
