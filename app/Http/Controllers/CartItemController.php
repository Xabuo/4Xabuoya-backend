<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Http\Resources\CartItemResource;
use App\Http\Controllers\CartItemController;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function index()
    {
        return CartItemResource::collection(
            CartItem::with(['customer', 'product'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::create($validated);

        return (new CartItemResource($cartItem))
            ->response()
            ->setStatusCode(201);
    }

    public function show(CartItem $cartItem)
    {
        return new CartItemResource(
            $cartItem->load(['customer', 'product'])
        );
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'product_id' => 'sometimes|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $cartItem->update($validated);

        return new CartItemResource($cartItem);
    }

    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();

        return response()->json([
            'message' => 'Cart item deleted successfully'
        ]);
    }
}