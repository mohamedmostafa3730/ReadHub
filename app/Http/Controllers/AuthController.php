<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //register
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $token = auth('api')->login($user);

        if (!$token) {
            return response()->json(
                ['message' => 'error message'],
                401
            );
        }
        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ], 201);
    }

    // login
    public function login(LoginRequest $loginRequest)
    {
        $data = $loginRequest->only('email', 'password');

        $token = Auth::guard('api')->attempt($data);

        if (!$token) {
            return response()->json(
                ['message' => 'Unauthorized'],
                401
            );
        }
        return response()->json([
            'access_token' => $token,
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }
    // refresh
    public function refresh()
    {
        $refresh_token = auth('api')->refresh();

        return response()->json([
            'refresh_token' => $refresh_token,
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }

    // me
    public function me()
    {
        $user = auth('api')->user();

        return response()->json($user);
    }

    // logout
    public function logout()
    {
        $user = auth('api')->user();

        auth('api')->logout();

        return response()->json([
            "message" => "Logged out successfully",
        ]);
    }
}