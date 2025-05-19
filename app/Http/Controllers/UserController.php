<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    // GET ALL DATA
    function index(){
        $users = User::all();
        return $users;
    }

    // STORE USER
    function store(Request $request)
    {
        // validation
        $validated_user = $request->validate([
            'name' => 'required|min:2|max:50',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|confirmed|min:6'
        ]);

        // hash the password before storing
        $validated_user['password'] = Hash::make($validated_user['password']);

        // adding using create
        $user = User::create($validated_user);

        //send feedback
        return response()->json([
            'message' => 'User created successfully.',
            'data' => $user 
        ], 201);
    }
}
