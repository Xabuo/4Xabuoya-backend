<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'customer';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'avatar_url',
        'password_hash',
        'contact_number',
        'address',
        'joined_date',
    ];

    protected $hidden = [
        'password_hash',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function ratingsAndReviews()
    {
        return $this->hasMany(RatingAndReview::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function notifications()
    {
        return $this->hasMany(CustomerNotification::class);
    }
}