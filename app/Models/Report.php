<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'report';

    protected $fillable = [
        'admin_id',
        'report_data',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}