<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'contact_number' => $this->contact_number,
            'inventory' => $this->inventory,
            'sales_report' => $this->sales_report,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}