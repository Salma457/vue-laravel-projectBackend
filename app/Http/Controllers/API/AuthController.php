<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // login
    public function login(Request $request)
    {
        // validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // get user by email
        $user = User::where('email', $request->email)->first();

        // check password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // create token using sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // send user data and token (avoid exposing full user model)
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role, // include role for routing in frontend
            ],
            'token' => $token,
        ], 200);
    }

    // logout
    public function logout(Request $request)
    {
        // remove token from current request
        $request->user()->currentAccessToken()->delete();

        // feedback
        return response()->json([
            'message' => 'Logged out'
        ], 200);
    }

    // get the user data if the token is valid
    public function user(Request $request)
    {
        return response()->json([
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'role' => $request->user()->role,
            ]
        ], 200);
    }
}
