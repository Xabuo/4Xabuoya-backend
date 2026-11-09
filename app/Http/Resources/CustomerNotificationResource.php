<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerNotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'order_id' => $this->order_id,
            'order_update' => $this->order_update,
            'admin_message' => $this->admin_message,
            'sent_date' => $this->sent_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}