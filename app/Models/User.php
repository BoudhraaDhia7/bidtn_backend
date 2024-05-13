<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property string first_name
 * @property string last_name
 * @property string email
 * @property string password
 * @property string remember_token
 * @property string email_verified_at
 * @property string password
 * @property string access_token
 * @property string refresh_token
 * @property int created_at
 * @property int updated_at
 * @property int deleted_at
 */

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Disable timestamps
     *
     * @var Date
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token', 'email_verified_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

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
     * Get the role that owns the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the media for the user.
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * Get the auctions for the user.
     */
    public function auctions(): HasMany
    {
        return $this->hasMany(Auction::class);
    }
    /**
     * Check if the user has any associated media.
     *
     * @return bool
     */
    public function hasMedia(): bool
    {
        return $this->media()->exists();
    }

    /*
     *Check if the the user in admin
     */
    public function isAdmin(): bool
    {
        return $this->role->id === 1;
    }

    /**
     * User realtion with jeton transaction
     */
    public function jetonTransactions(): HasMany
    {
        return $this->hasMany(JetonTransaction::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }   

    /**
     * Get the auctions that the user has participated in.
     */
    public function auctionParticipations(): HasMany
    {
        return $this->hasMany(AuctionParticipant::class, 'user_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class , 'user_id');
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [
            'user' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'balance' => $this->balance 
            ]
        ];
    }
}
