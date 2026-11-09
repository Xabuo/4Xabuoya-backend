<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminNotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'admin_id' => $this->admin_id,
            'order_id' => $this->order_id,
            'title' => $this->title,
            'body' => $this->body,
            'sent_date' => $this->sent_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}