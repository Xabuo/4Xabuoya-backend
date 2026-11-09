<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;

class GuestController extends Controller
{
    public function products()
    {
        return ProductResource::collection(
            Product::with('category')->get()
        );
    }
}