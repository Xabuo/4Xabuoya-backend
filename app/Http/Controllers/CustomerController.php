<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()

    
    {
        return response()->json(Customer::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'avatar_url' => 'nullable|string',
            'password_hash' => 'required|string',
            'contact_number' => 'nullable|string',
            'address' => 'nullable|string',
            'joined_date' => 'nullable|date',
        ]);

        $validated['password_hash'] = Hash::make($validated['password_hash']);

        $customer = Customer::create($validated);

        return response()->json($customer, 201);
    }

    // ADMIN: VIEW ALL CUSTOMERS
public function adminIndex()
{
    return response()->json(
        Customer::select([
            'id',
            'first_name',
            'last_name',
            'email',
            'avatar_url',
            'contact_number',
            'address',
            'joined_date',
            'created_at',
        ])->latest()->get()
    );
}

    public function show(string $id)
    {
        return response()->json(Customer::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:customers,email,' . $id,
            'avatar_url' => 'sometimes|nullable|string',
            'password_hash' => 'sometimes|string',
            'phone' => 'sometimes|nullable|string',
            'address' => 'sometimes|nullable|string',
            'joined_date' => 'sometimes|nullable|date',
        ]);

        if (isset($validated['password_hash'])) {
            $validated['password_hash'] = Hash::make($validated['password_hash']);
        }

        $customer->update($validated);

        return response()->json($customer);
    }

    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json([
            'message' => 'Customer deleted successfully'
        ]);
    }
}