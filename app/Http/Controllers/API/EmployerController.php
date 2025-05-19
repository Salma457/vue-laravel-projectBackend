<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// import the Employer model
use App\Models\Employer;

class EmployerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emps =  Employer::all();
        return $emps;
    }

    /**
     * Store a newly created resource in storage.
     */
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
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
