<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Http\Resources\WishlistResource;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        return WishlistResource::collection(
            Wishlist::with(['customer', 'product'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::create($validated);

        return (new WishlistResource($wishlist))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Wishlist $wishlist)
    {
        return new WishlistResource(
            $wishlist->load(['customer', 'product'])
        );
    }

    public function update(Request $request, Wishlist $wishlist)
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'product_id' => 'sometimes|exists:products,id',
        ]);

        $wishlist->update($validated);

        return new WishlistResource($wishlist);
    }

    public function destroy(Wishlist $wishlist)
    {
        $wishlist->delete();

        return response()->json([
            'message' => 'Wishlist item deleted successfully'
        ]);
    }
}