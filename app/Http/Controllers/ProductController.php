<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\ProductController;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(
            Product::with('category')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:category,id',
            'product_name' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_level' => 'required|integer|min:0',
            'image_url' => 'nullable|string',
            'product_review' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        $product = Product::create($validated);

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Product $product)
    {
        return new ProductResource(
            $product->load('category')
        );
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|exists:category,id',
            'product_name' => 'sometimes|string|max:255',
            'product_description' => 'sometimes|nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'stock_level' => 'sometimes|integer|min:0',
            'image_url' => 'sometimes|nullable|string',
            'product_review' => 'sometimes|nullable|string',
            'rating' => 'sometimes|nullable|numeric|min:0|max:5',
        ]);

        $product->update($validated);

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}