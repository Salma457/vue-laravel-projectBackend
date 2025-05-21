<?php

namespace App\Http\Controllers;

use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


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
        // validation with profile_picture file optional
        $validated_user = $request->validate([
            'name' => 'required|min:2|max:50',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|confirmed|min:6',
            'role' => 'required',
            'profile_picture' => 'nullable|max:2048', // max 2MB
        ]);
    
        // hash password
        $validated_user['password'] = Hash::make($validated_user['password']);
    
        // handle profile picture upload if exists
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated_user['profile_picture'] = $path;
        }
    
        // create user
        $user = User::create($validated_user);
    
        \Log::info('New user created:', $user->toArray());
    
        // return user data including full URL to profile picture
        return response()->json([
            'message' => 'User created successfully.',
            'data' => $user->toArray() + ['profile_picture_url' => $user->profile_picture ? asset('storage/'.$user->profile_picture) : null]
        ], 201);
    }
    }
