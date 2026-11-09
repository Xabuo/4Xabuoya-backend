<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'category_name' => $this->category?->name,
            'product_name' => $this->product_name,
            'product_description' => $this->product_description,
            'price' => $this->price,
            'rating' => $this->rating,
            'stock_level' => $this->stock_level,
            'sold' => $this->sold,
            'image_url' => $this->image_url,
            'product_review' => $this->product_review,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
        ];
    }
}