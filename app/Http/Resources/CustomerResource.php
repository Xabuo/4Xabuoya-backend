<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'avatar_url' => $this->avatar_url,
            'address' => $this->address,
            'contact_number' => $this->contact_number,
            'joined_date' => $this->joined_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}