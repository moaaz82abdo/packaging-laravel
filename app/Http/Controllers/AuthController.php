<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|unique:users',
            'name' => 'required|unique:users',
            'address' => 'required|string|max:255',
            'phone' => 'required|digits:11',
            'password' => 'required|min:8',
            'email' => 'required|email|unique:users'
        ]);

        $customer = User::create([
            'customer_name' => $request->customer_name,
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'email' => $request->email
        ]);

        return response()->json($customer, 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $customer = User::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token], 200);
    }
}
