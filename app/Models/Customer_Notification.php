<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer_Notification extends Model
{
    protected $table = 'customer_notification';
    
    protected $fillable = [
        'customer_id',
        'order_id',
        'order_update',
        'admin_message',
        'sent_date',
    ];

    protected $casts = [
        'sent_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}