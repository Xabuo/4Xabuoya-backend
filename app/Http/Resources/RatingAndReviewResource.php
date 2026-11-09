<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingAndReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'customer_id' => $this->customer_id,

            'customer' => $this->whenLoaded('customer', function () {
                return [
                    'first_name' => $this->customer->first_name,
                    'last_name' => $this->customer->last_name,
                ];
            }),

            'score' => $this->score,
            'comment' => $this->comment,
            'date' => $this->date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}