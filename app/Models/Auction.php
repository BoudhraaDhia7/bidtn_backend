<?php

namespace App\Models;

use App\Models\Product;
use App\Traits\ApplyQueryScopes;
use App\Traits\PaginationParams;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Auction Model
 */
class Auction extends Model
{
    use HasFactory, ApplyQueryScopes, PaginationParams;

    public $timestamps = false;

    protected $fillable = ['title', 'description', 'starting_price', 'is_finished', 'starting_user_number', 'winner_id', 'is_confirmed', 'user_id', 'start_date', 'end_date', 'created_at', 'updated_at'];

    protected $appends = ['added_by', 'winner_name'];

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
     * Relationship with Product
     */
    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'auction_id');
    }

    /**
     * Polymorphic relationship with Media
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * Scope to filter by keyword in title or description
     */
    public function scopeFilterByKeyword($query, $keyword)
    {
        if ($keyword !== null) {
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('auctions.title', 'like', '%' . $keyword . '%')->orWhere('auctions.description', 'like', '%' . $keyword . '%');
            });
        }
        return $query;
    }

    /**
     * Scope to filter by category
     */
    public function scopeFilterByCategory($query, $category)
    {
        if ($category !== null) {
            $query->whereHas('product.categories', function ($query) use ($category) {
                $query->whereIn('categories.name', $category);
            });
        }
        return $query;
    }

    /**
     * Scope to filter by parent category ID
     */
    public function scopeFilterByParentCategory($query, $parentCategoryId)
    {
        if ($parentCategoryId !== null) {
            $query->whereHas('product.categories', function ($query) use ($parentCategoryId) {
                $query->where('categories.id', $parentCategoryId);
            });
        }
        return $query;
    }

    /**
     * Scope to filter by child category ID
     */
    public function scopeFilterByChildCategory($query, $childCategoryId)
    {
        if ($childCategoryId !== null) {
            $query->whereHas('product.categories', function ($query) use ($childCategoryId) {
                $query->where('categories.id', $childCategoryId);
            });
        }
        return $query;
    }

    /**
     * Scope to filter by minimum price
     */
    public function scopeFilterByMinPrice($query, $minPrice)
    {
        if ($minPrice !== null) {
            $query->where('auctions.starting_price', '>=', $minPrice);
        }
        return $query;
    }

    /**
     * Scope to filter by maximum price
     */
    public function scopeFilterByMaxPrice($query, $maxPrice)
    {
        if ($maxPrice !== null) {
            $query->where('auctions.starting_price', '<=', $maxPrice);
        }
        return $query;
    }

    /**
     * Scope to filter by minium user number to start the auction
     */
    public function scopeFilterByStartingUserNumber($query, $startingUserNumber)
    {
        if ($startingUserNumber !== null) {
            $query->where('auctions.starting_user_number', '>=', $startingUserNumber);
        }
        return $query;
    }

    /**
     * Scope to filter by start date
     */
    public function scopeFilterByStartDate($query, $startDate)
    {
        if ($startDate !== null) {
            $query->where('auctions.start_date', '>=', $startDate);
        }
        return $query;
    }

    /**
     * Get the participants in the auction
     */
    public function participants(): HasMany
    {
        return $this->hasMany(AuctionParticipant::class, 'auction_id');
    }

    /**
     * Get the transactions related to the auction
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'auction_id');
    }

    /**
     * Check if a given user ID is a participant in the auction
     */
    public function isParticipant($user_id)
    {
        return $this->participants()->where('user_id', $user_id)->exists();
    }

    /*
     * Get the number of participants in the auction
     */
    public function getParticipantsCount()
    {
        return $this->participants()->count();
    }

    /**
     * Check if the given bid amount is the highest for the auction
     */
    public function isHighestBid($bidAmount)
    {
        return $this->transactions()->max('amount') < $bidAmount;
    }

    /*
     * Get the highest bid amount in the auction
     */
    public function getHighestBid()
    {
        return $this->transactions()->max('amount');
    }

    /*
     * Check if the auction is refundable
     */
    public function isRefundable()
    {
        return $this->transactions()->where('type', 'refund')->exists();
    }

    /**
     * Retun the bid amount of a user id in the auction
     */
    public function getBidAmount($user_id)
    {
        return $this->transactions()->where('user_id', $user_id)->sum('amount');
    }

    /*
      * Mark all transaction as refunded
    */
    public function markAllTransactionAsRefunded($user_id)
    {
        $this->transactions()->where('user_id', $user_id)->update(['type' => 'refund']);
    }

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor to get the full name of the user who added the auction
     */
    public function getAddedByAttribute()
    {
        return $this->user ? $this->user->first_name . ' ' . $this->user->last_name : null;
    }

    /**
     * Accessor to get the full name of the auction winner
     */
    public function getWinnerNameAttribute()
    {
        $user = User::find($this->winner_id);
        return $user ? $user->first_name . ' ' . $user->last_name : null;
    }

    /**
     * Check if a given user ID is a participant in the auction
     */
    public function isAuctionParticipant($user_id)
    {
        return $this->participants()->where('user_id', $user_id)->exists();
    }
}
