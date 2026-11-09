<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    public function index()
    {
        return response()->json(
            ShoppingCart::with(['customer', 'product'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = ShoppingCart::create($validated);

        return response()->json($cart, 201);
    }

    public function show(ShoppingCart $shoppingCart)
    {
        return response()->json(
            $shoppingCart->load(['customer', 'product'])
        );
    }

    public function update(Request $request, ShoppingCart $shoppingCart)
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'product_id' => 'sometimes|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $shoppingCart->update($validated);

        return response()->json($shoppingCart);
    }

    public function destroy(ShoppingCart $shoppingCart)
    {
        $shoppingCart->delete();

        return response()->json([
            'message' => 'Shopping cart item deleted successfully'
        ]);
    }
}