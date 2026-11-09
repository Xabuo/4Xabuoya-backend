<?php

namespace App\Http\Controllers;

use App\Models\CustomerNotification;
use App\Http\Resources\CustomerNotificationResource;
use Illuminate\Http\Request;

class CustomerNotificationController extends Controller
{
    public function index()
    {
        return CustomerNotificationResource::collection(
            CustomerNotification::with(['customer', 'order'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'order_update' => 'required|string',
            'admin_message' => 'nullable|string',
            'sent_date' => 'nullable|date',
        ]);

        $notification = CustomerNotification::create($validated);

        return (new CustomerNotificationResource($notification))
            ->response()
            ->setStatusCode(201);
    }

    public function show(CustomerNotification $customerNotification)
    {
        return new CustomerNotificationResource(
            $customerNotification->load(['customer', 'order'])
        );
    }

    public function update(
        Request $request,
        CustomerNotification $customerNotification
    ) {
        $validated = $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'order_id' => 'sometimes|nullable|exists:orders,id',
            'order_update' => 'sometimes|string',
            'admin_message' => 'sometimes|nullable|string',
            'sent_date' => 'sometimes|nullable|date',
        ]);

        $customerNotification->update($validated);

        return new CustomerNotificationResource($customerNotification);
    }

    public function destroy(CustomerNotification $customerNotification)
    {
        $customerNotification->delete();

        return response()->json([
            'message' => 'Customer notification deleted successfully'
        ]);
    }
}