<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'order_date' => $this->order_date,
            'total_amount' => $this->total_amount,
            'shipping_address' => $this->shipping_address,
            'payment_status' => $this->payment_status,
            'order_status' => $this->order_status,
            'tracking_number' => $this->tracking_number,
            'shipped_at' => $this->shipped_at,
            'delivered_at' => $this->delivered_at,
            'cancelled_at' => $this->cancelled_at,
            
            'contact_number' => $this->contact_number,
            'subtotal' => $this->subtotal,
'shipping_fee' => $this->shipping_fee,
'tax' => $this->tax,
'coupon_code' => $this->coupon_code,
'discount_amount' => $this->discount_amount,
'grand_total' => $this->grand_total,
'payment_method' => $this->payment_method,

            // Customer information
            'customer' => $this->whenLoaded('customer', function () {
                return [
                    'id' => $this->customer->id,
                    'first_name' => $this->customer->first_name,
                    'last_name' => $this->customer->last_name,
                    'email' => $this->customer->email,
                ];
            }),

            // Purchased products
            'items' => $this->whenLoaded('items', function () {
                return $this->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,

                        'product' => $item->product ? [
                            'id' => $item->product->id,
                            'product_name' => $item->product->product_name,
                            'image_url' => $item->product->image_url,
                        ] : null,
                    ];
                });
            }),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
