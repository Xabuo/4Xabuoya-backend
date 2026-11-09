<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingAndReview extends Model
{
    protected $table = 'ratings_and_reviews';

    protected $fillable = [
        'product_id',
        'customer_id',
        'score',
        'comment',
        'date',
    ];

    protected $casts = [
        'score' => 'integer',
        'date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}