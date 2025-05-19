<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class EmployerController extends Controller
{

    // get all employers
    public function index()
    {
        $emps =  Employer::all();
        return $emps;
    }

    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name'           => 'required|string|max:255',
    //         'email'          => 'required|string|email|unique:users,email',
    //         'password'       => 'required|string|min:6',
    //         'company_name'   => 'required|string|max:255',
    //         'location'       => 'nullable|string',
    //         'company_website'=> 'nullable|url',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 422);
    //     }

    //     $user = User::create([
    //         'name'     => $request->name,
    //         'email'    => $request->email,
    //         'password' => Hash::make($request->password),
    //         'role'     => 'employer',
    //     ]);

    //     Employer::create([
    //         'user_id'        => $user->id,
    //         'company_name'   => $request->company_name,
    //         'location'       => $request->location,
    //         'company_website'=> $request->company_website,
    //     ]);

    //     return response()->json(['message' => 'Employer registered successfully'], 201);
    // }


    public function store(Request $request)
    {
        // validation
        $validated_employer = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'company_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'company_website' => 'required|url',
            'company_logo' => 'required|string',
            'phone' => 'required|string|max:20',
            'bio' => 'required|string',
        ]);
        
    
        // create
        $employer = Employer::create($validated_employer);
    
        // feedback
        return response()->json([
            'message' => 'Employer created successfully',
            'data' => $employer
        ], 201);
    }



    public function login(Request $request)
    {
        // only the email and password
        $credentials = $request->only('email', 'password');

        // check email
        $user = User::where('email', $credentials['email'])->first();

        // check the password
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // check authrization [should not be here]
        if ($user->role !== 'employer') {
            return response()->json(['error' => 'Not authorized as employer'], 403);
        }

        // gennerate the token
        $token = $user->createToken('employer_token')->plainTextToken;

        // in the response: message, token, user
        return response()->json([
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user
        ]);
    }

public function profile(Request $request)
{
    $user = $request->user();

    // تأكد إنه صاحب عمل
    if ($user->role !== 'employer') {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $employer = Employer::where('user_id', $user->id)->first();

    return response()->json([
        'user'     => $user,
        'employer' => $employer,
    ]);
}

public function updateProfile(Request $request)
{
    $user = $request->user();

    if ($user->role !== 'employer') {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $validator = Validator::make($request->all(), [
        'name'            => 'sometimes|string|max:255',
        'company_name'    => 'sometimes|string|max:255',
        'location'        => 'nullable|string',
        'company_website' => 'nullable|url',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // تحديث بيانات المستخدم
    $user->update([
        'name' => $request->name ?? $user->name,
    ]);

    // تحديث بيانات صاحب العمل
    $employer = Employer::where('user_id', $user->id)->first();
    $employer->update([
        'company_name'    => $request->company_name ?? $employer->company_name,
        'location'        => $request->location ?? $employer->location,
        'company_website' => $request->company_website ?? $employer->company_website,
    ]);

    return response()->json([
        'message'  => 'Profile updated successfully',
        'user'     => $user,
        'employer' => $employer,
    ]);
}
public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logged out successfully'
    ]);
}


public function applications()
{
    $employer = Auth::user()->employer;

    $applications = Application::with(['job', 'candidate'])
        ->whereHas('job', function ($query) use ($employer) {
            $query->where('employer_id', $employer->id);
        })
        ->get();

    return response()->json($applications);
}
public function updateApplicationStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,approved,rejected'
    ]);

    $application = Application::findOrFail($id);

    $employer = Auth::user()->employer;

    if ($application->job->employer_id != $employer->id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $application->status = $request->status;
    $application->save();

    return response()->json(['message' => 'Status updated successfully']);
}
public function deleteAccount()
{
    $user = Auth::user();

    // حذف صاحب العمل وكل ما يتعلق به حسب العلاقات
    $user->employer()->delete();
    $user->delete();

    return response()->json(['message' => 'Account deleted successfully']);
}
public function changePassword(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->old_password, $user->password)) {
        return response()->json(['error' => 'Old password is incorrect'], 400);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return response()->json(['message' => 'Password updated successfully']);
}
}
