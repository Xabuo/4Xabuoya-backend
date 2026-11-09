<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;

class OrderController extends Controller
{
        public function index(Request $request)
{
    return OrderResource::collection(
        $request->user()
            ->orders()
            ->with(['customer', 'items.product'])
            ->latest()
            ->get()
    );
}

    
public function store(Request $request)
{
    $validated = $request->validate([
        'shipping_address' => 'required|string',
        'payment_method' => 'required|string|max:255',
        'coupon_code' => 'nullable|string|max:50',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|integer|exists:product,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    $customer = $request->user();

    if (!$customer) {
        return response()->json([
            'message' => 'Customer is not authenticated.',
        ], 401);
    }

    $order = DB::transaction(function () use ($validated, $customer) {

        $subtotal = 0;
        $orderItems = [];

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);

            $quantity = $item['quantity'];
            $unitPrice = (float) $product->price;

            $subtotal += $unitPrice * $quantity;

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ];
        }

        $couponCode = strtoupper(trim($validated['coupon_code'] ?? ''));

        // Default delivery fee
        $shippingFee = 50;
        $discountAmount = 0;

        // Coupon validation and calculation
        if ($couponCode !== '') {

            switch ($couponCode) {

                case 'WELCOME150':
                    if ($subtotal < 1000) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'coupon_code' => 'Minimum spend for this coupon is ₱20.',
                        ]);
                    }

                    $discountAmount = min(150, $subtotal);
                    break;

                case 'SAVE10':
                    if ($subtotal < 1000) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'coupon_code' => 'Minimum spend for this coupon is ₱1,000.',
                        ]);
                    }

                    $discountAmount = min($subtotal * 0.10, 1500);
                    break;

                case 'SAVE12':
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'coupon_code' => 'This coupon has already expired.',
                    ]);

                case 'SAVE25':
                    if ($subtotal < 50) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'coupon_code' => 'Minimum spend for this coupon is ₱50.',
                        ]);
                    }

                    if (now()->greaterThan(
                        \Carbon\Carbon::parse('2026-11-18 23:59:59')
                    )) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'coupon_code' => 'This coupon has expired.',
                        ]);
                    }

                    $discountAmount = min($subtotal * 0.25, 50);
                    break;

                case 'FREESHIP':
                    if ($subtotal < 150) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'coupon_code' => 'Minimum spend for free shipping is ₱150.',
                        ]);
                    }

                    $shippingFee = 0;
                    break;

                default:
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'coupon_code' => 'Invalid coupon code.',
                    ]);
            }
        }

        $tax = 0;
        $grandTotal = max(0, $subtotal + $shippingFee + $tax - $discountAmount);

        $order = Order::create([
            'customer_id' => $customer->id,
            'order_date' => now()->toDateString(),
            'total_amount' => $grandTotal,
            'shipping_address' => $validated['shipping_address'],
            'payment_status' => 'pending',
            'order_status' => 'Pending',
            'contact_number' => $customer->contact_number,
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'tax' => $tax,
            'grand_total' => $grandTotal,
            'payment_method' => $validated['payment_method'],
            'coupon_code' => $couponCode ?: null,
            'discount_amount' => $discountAmount,
        ]);

        $order->items()->createMany($orderItems);

        return $order;
    });

    return (new OrderResource(
        $order->load(['customer', 'items.product'])
    ))->response()->setStatusCode(201);
}


    public function show(Request $request, $id)
{
    $order = $request->user()
        ->orders()
        ->with(['customer', 'items.product'])
        ->findOrFail($id);

    return new OrderResource($order);
}

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|exists:customer,id',
            'order_date' => 'sometimes|nullable|date',
            'total_amount' => 'sometimes|numeric|min:0',
            'shipping_address' => 'sometimes|nullable|string',
            'payment_status' => 'sometimes|string',
            'order_status' => 'sometimes|in:Pending,Processing,Packed,Shipped,Delivered,Cancelled',            'tracking_number' => 'sometimes|nullable|string|max:255',
            'contact_number' => 'sometimes|nullable|string',
            'subtotal' => 'sometimes|numeric|min:0',
            'shipping_fee' => 'sometimes|numeric|min:0',
            'tax' => 'sometimes|numeric|min:0',
            'grand_total' => 'sometimes|numeric|min:0',
            'payment_method' => 'sometimes|nullable|string',
        ]);

        $order->update($validated);

        if (isset($validated['order_status'])) {
            $status = $validated['order_status'];

            if ($status === 'Shipped' && !$order->shipped_at) {
                $order->shipped_at = now();
            }

            if ($status === 'Delivered' && !$order->delivered_at) {
                $order->delivered_at = now();
            }

            if ($status === 'Cancelled' && !$order->cancelled_at) {
                $order->cancelled_at = now();
            }

            $order->save();
        }

        return new OrderResource(
            $order->load(['customer', 'items.product'])
        );
    }

    public function cancel(Request $request, $id)
{
    $order = $request->user()
        ->orders()
        ->findOrFail($id);

    if (in_array($order->order_status, [
        'Packed',
        'Shipped',
        'Delivered',
        'Cancelled'
    ])) {
        return response()->json([
            'message' => 'This order can no longer be cancelled.',
        ], 422);
    }

    $order->update([
        'order_status' => 'Cancelled',
        'cancelled_at' => now(),
    ]);

    return response()->json([
        'message' => 'Order cancelled successfully.',
        'data' => new OrderResource(
            $order->load(['customer', 'items.product'])
        ),
    ]);
}

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully'
        ]);
    }


// ADMIN: VIEW ALL CUSTOMER ORDERS
public function adminIndex()
{
    return OrderResource::collection(
        Order::with(['customer', 'items.product'])
            ->latest()
            ->get()
    );
}

// ADMIN: UPDATE ORDER STATUS
public function adminUpdateStatus(Request $request, Order $order)
{
    $validated = $request->validate([
        'order_status' => 'required|in:Pending,Processing,Packed,Ready for Pickup,Shipped,Delivered,Cancelled',
    ]);

    $status = $validated['order_status'];

    // Prevent changing an already cancelled order.
    if ($order->order_status === 'Cancelled') {
        return response()->json([
            'message' => 'This order has already been cancelled and cannot be changed.',
        ], 422);
    }

    // Check if this is the first time the order becomes Delivered.
    $firstDelivery = $status === 'Delivered'
        && $order->order_status !== 'Delivered';

    DB::transaction(function () use ($order, $status, $firstDelivery) {

        $order->order_status = $status;

        if ($status === 'Shipped' && !$order->shipped_at) {
            $order->shipped_at = now();
        }

        if ($status === 'Delivered' && !$order->delivered_at) {
            $order->delivered_at = now();
        }

        if ($status === 'Cancelled' && !$order->cancelled_at) {
            $order->cancelled_at = now();
        }

        $order->save();

        // Increase sold count only on first delivery.
        if ($firstDelivery) {
            $orderItems = OrderItem::with('product')
                ->where('order_id', $order->id)
                ->get();

            foreach ($orderItems as $item) {
                if ($item->product) {
                    $item->product->increment(
                        'sold',
                        $item->quantity
                    );
                }
            }
        }
    });

    return new OrderResource(
        $order->load(['customer', 'items.product'])
        );
    }
}