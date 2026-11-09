<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'order';

    protected $fillable = [
    'customer_id',
    'order_date',
    'total_amount',
    'shipping_address',
    'payment_status',
    'order_status',
    'tracking_number',
    'shipped_at',
    'delivered_at',
    'cancelled_at',
    'contact_number',
    'subtotal',
    'shipping_fee',
    'tax',
    'grand_total',
    'payment_method',
    'coupon_code',
    'discount_amount',
];

    protected $casts = [
    'order_date' => 'date',
    'total_amount' => 'decimal:2',
    'subtotal' => 'decimal:2',
    'shipping_fee' => 'decimal:2',
    'tax' => 'decimal:2',
    'grand_total' => 'decimal:2',
    'shipped_at' => 'datetime',
    'delivered_at' => 'datetime',
    'cancelled_at' => 'datetime',
    'discount_amount' => 'decimal:2',
];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
{
    return $this->hasMany(OrderItem::class, 'order_id');
}

    public function customerNotifications(): HasMany
    {
        return $this->hasMany(CustomerNotification::class);
    }

    public function adminNotifications(): HasMany
    {
        return $this->hasMany(AdminNotification::class);
    }
}