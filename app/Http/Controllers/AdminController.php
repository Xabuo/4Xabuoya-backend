<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Resources\AdminResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return AdminResource::collection(Admin::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email',            'password' => 'required|string|min:8',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'inventory' => 'required|integer|min:0',
            'sales_report' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $admin = Admin::create($validated);

        return (new AdminResource($admin))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Admin $admin)
    {
        return new AdminResource($admin);
    }

    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:admin,email,' . $admin->id,            'password' => 'sometimes|string|min:8',
            'address' => 'sometimes|nullable|string',
            'contact_number' => 'sometimes|nullable|string',
            'inventory' => 'sometimes|integer|min:0',
            'sales_report' => 'sometimes|nullable|string',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $admin->update($validated);

        return new AdminResource($admin);
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();

        return response()->json([
            'message' => 'Admin deleted successfully'
        ]);
    }
}