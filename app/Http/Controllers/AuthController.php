<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // CUSTOMER REGISTRATION
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customer,email',
            'contact_number' => 'required|string|max:30',
            'address' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $customer = Customer::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'contact_number' => $validated['contact_number'],
            'address' => $validated['address'],
            'password_hash' => Hash::make($validated['password']),
            'joined_date' => now()->toDateString(),
        ]);

        $token = $customer->createToken('customer-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful!',
            'customer' => $customer,
            'token' => $token,
        ], 201);
    }

    // CUSTOMER LOGIN
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $customer = Customer::where('email', $validated['email'])->first();

        if (!$customer || !Hash::check($validated['password'], $customer->password_hash)) {
            return response()->json([
                'message' => 'Invalid email or password.',
            ], 401);
        }

        $token = $customer->createToken('customer-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful!',
            'customer' => $customer,
            'token' => $token,
        ]);
    }

    // CUSTOMER LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful!',
        ]);
    }

    // GET LOGGED-IN CUSTOMER
    public function me(Request $request)
    {
        return response()->json([
            'customer' => $request->user(),
        ]);
    }
}
