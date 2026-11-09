<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin_Notification extends Model
{
     protected $table = 'admin_notification';
    protected $fillable = [
        'admin_id',
        'order_id',
        'title',
        'body',
        'sent_date',
    ];

    protected $casts = [
    'sent_date' => 'datetime',
];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}