<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Http\Resources\OrderItemResource;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        return OrderItemResource::collection(
            OrderItem::with(['order', 'product'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $orderItem = OrderItem::create($validated);

        return (new OrderItemResource($orderItem))
            ->response()
            ->setStatusCode(201);
    }

    public function show(OrderItem $orderItem)
    {
        return new OrderItemResource(
            $orderItem->load(['order', 'product'])
        );
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        $validated = $request->validate([
            'order_id' => 'sometimes|exists:orders,id',
            'product_id' => 'sometimes|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
            'unit_price' => 'sometimes|numeric|min:0',
        ]);

        $orderItem->update($validated);

        return new OrderItemResource($orderItem);
    }

    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();

        return response()->json([
            'message' => 'Order item deleted successfully'
        ]);
    }
}