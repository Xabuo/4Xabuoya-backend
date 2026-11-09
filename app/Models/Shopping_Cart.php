<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shopping_Cart extends Model
{
    protected $table = 'shopping_cart';
    
    protected $fillable = [
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}