<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    protected $table = 'product';
    
    protected $fillable = [
    'category_id',
    'product_name',
    'product_description',
    'price',
    'stock_level',
    'image_url',
    'product_review',
    'rating',
    'sold',
];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}