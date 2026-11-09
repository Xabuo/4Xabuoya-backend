<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Http\Resources\AdminNotificationResource;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index()
    {
        return AdminNotificationResource::collection(
            AdminNotification::with(['admin', 'order'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'order_id' => 'nullable|exists:orders,id',
            'title' => 'required|string',
            'body' => 'required|string',
            'sent_date' => 'nullable|date',
        ]);

        $notification = AdminNotification::create($validated);

        return (new AdminNotificationResource($notification))
            ->response()
            ->setStatusCode(201);
    }

    public function show(AdminNotification $adminNotification)
    {
        return new AdminNotificationResource(
            $adminNotification->load(['admin', 'order'])
        );
    }

    public function update(
        Request $request,
        AdminNotification $adminNotification
    ) {
        $validated = $request->validate([
            'admin_id' => 'sometimes|exists:admins,id',
            'order_id' => 'sometimes|nullable|exists:orders,id',
            'title' => 'sometimes|string',
            'body' => 'sometimes|string',
            'sent_date' => 'sometimes|nullable|date',
        ]);

        $adminNotification->update($validated);

        return new AdminNotificationResource($adminNotification);
    }

    public function destroy(AdminNotification $adminNotification)
    {
        $adminNotification->delete();

        return response()->json([
            'message' => 'Admin notification deleted successfully'
        ]);
    }
}