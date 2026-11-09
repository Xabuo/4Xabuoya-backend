<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'contact_number',
        'inventory',
        'sales_report',
    ];

    protected $hidden = [
        'password',
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function notifications()
    {
        return $this->hasMany(AdminNotification::class);
    }
}