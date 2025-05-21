<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function index(){
        $candidates = Candidate::all();
        return $candidates;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:candidates,user_id',
            'current_job' => 'nullable|string',
            'experience_level' => 'nullable|string',
            'highest_qualification' => 'nullable|string',
            'bio' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // max 5MB
            'linkedin_profile' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        // Store the resume file if uploaded
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $validated['resume'] = $path;
        }

        $candidate = Candidate::create($validated);

        // Optionally add a full URL for the resume in response
        $candidate->resume_url = $candidate->resume ? asset('storage/' . $candidate->resume) : null;

        return response()->json([
            'message' => 'Candidate created successfully.',
            'candidate' => $candidate
        ], 201);
    }


    public function getByUserId($user_id)
    {
        $candidate = Candidate::where('user_id', $user_id)->first();
        if (!$candidate) {return response()->json(['message' => 'Candidate not found'], 404);}
        return response()->json($candidate);
    }
      
}
