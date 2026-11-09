<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Http\Resources\ReportResource;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;

class ReportController extends Controller
{
    public function index()
    {
        return ReportResource::collection(
            Report::with('admin')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'report_data' => 'required|string',
        ]);

        $report = Report::create($validated);

        return (new ReportResource($report))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Report $report)
    {
        return new ReportResource(
            $report->load('admin')
        );
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'admin_id' => 'sometimes|exists:admins,id',
            'report_data' => 'sometimes|string',
        ]);

        $report->update($validated);

        return new ReportResource($report);
    }

    public function destroy(Report $report)
    {
        $report->delete();

        return response()->json([
            'message' => 'Report deleted successfully'
        ]);
        
    }

// ADMIN: SALES REPORT SUMMARY
public function salesSummary()
{
    $totalOrders = Order::count();

    $deliveredOrders = Order::where(
        'order_status',
        'Delivered'
    )->count();

    $cancelledOrders = Order::where(
        'order_status',
        'Cancelled'
    )->count();

    $totalRevenue = Order::where(
        'order_status',
        'Delivered'
    )->sum('grand_total');

    $recentOrders = Order::with(['customer', 'items.product'])
        ->latest()
        ->take(10)
        ->get();

    return response()->json([
        'total_orders' => $totalOrders,
        'delivered_orders' => $deliveredOrders,
        'cancelled_orders' => $cancelledOrders,
        'total_revenue' => $totalRevenue,
        'recent_orders' => OrderResource::collection(
            $recentOrders
        )->resolve(),
        ]);
    }

}