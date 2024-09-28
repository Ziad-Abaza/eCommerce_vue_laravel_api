<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
     protected $fillable = [
        'name', 'email', 'password', 'phone', 'address', 
        'token', 
        'token_expiration', 'is_active'
    ];

    public function notifications()
    {
    return $this->belongsToMany(Notification::class, 'users_has_notifications')
                    ->withPivot('read_at')
                    ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(OrderDetails::class, 'user_id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'user_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorites', 'user_id', 'product_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
}
