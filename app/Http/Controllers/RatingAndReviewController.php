<?php

namespace App\Http\Controllers;

use App\Models\RatingAndReview;
use App\Models\OrderItem;
use App\Http\Resources\RatingAndReviewResource;
use Illuminate\Http\Request;

class RatingAndReviewController extends Controller
{
    // DISPLAY ALL REVIEWS
    public function index()
    {
        return RatingAndReviewResource::collection(
            RatingAndReview::with(['product', 'customer'])->get()
        );
    }

    // SUBMIT A REVIEW
    public function store(Request $request)
    {
        $customer = $request->user();

        $validated = $request->validate([
            'product_id' => 'required|exists:product,id',
            'score' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $hasPurchased = OrderItem::where(
            'product_id',
            $validated['product_id']
        )
        ->whereHas('order', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id)
                  ->where('order_status', 'Delivered');
        })
        ->exists();

        if (!$hasPurchased) {
            return response()->json([
                'message' => 'You can only review products from delivered orders.'
            ], 403);
        }

        $alreadyReviewed = RatingAndReview::where(
            'product_id',
            $validated['product_id']
        )
        ->where('customer_id', $customer->id)
        ->exists();

        if ($alreadyReviewed) {
            return response()->json([
                'message' => 'You have already reviewed this product.'
            ], 422);
        }

        $review = RatingAndReview::create([
            'product_id' => $validated['product_id'],
            'customer_id' => $customer->id,
            'score' => $validated['score'],
            'comment' => $validated['comment'] ?? null,
            'date' => now()->toDateString(),
        ]);

        return (new RatingAndReviewResource(
            $review->load(['product', 'customer'])
        ))
            ->response()
            ->setStatusCode(201);
    }

    // DISPLAY ONE REVIEW
    public function show(RatingAndReview $ratingAndReview)
    {
        return new RatingAndReviewResource(
            $ratingAndReview->load(['product', 'customer'])
        );
    }

    // UPDATE A REVIEW
    public function update(
        Request $request,
        RatingAndReview $ratingAndReview
    ) {
        if ($request->user()->id !== $ratingAndReview->customer_id) {
            return response()->json([
                'message' => 'You are not authorized to edit this review.'
            ], 403);
        }

        $validated = $request->validate([
            'score' => 'sometimes|integer|min:1|max:5',
            'comment' => 'sometimes|nullable|string|max:1000',
        ]);

        $ratingAndReview->update($validated);

        return new RatingAndReviewResource(
            $ratingAndReview->load(['product', 'customer'])
        );
    }

    // DELETE A REVIEW
    public function destroy(
        Request $request,
        RatingAndReview $ratingAndReview
    ) {
        if ($request->user()->id !== $ratingAndReview->customer_id) {
            return response()->json([
                'message' => 'You are not authorized to delete this review.'
            ], 403);
        }

        $ratingAndReview->delete();

        return response()->json([
            'message' => 'Review deleted successfully.'
        ]);
    }
}
