<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    // Method: storing application
    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:usersjobs,id',
            'contact_phone' => 'required|string',
            'resume_snapshot' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);
    
        // Find the candidate associated with the logged-in user
        $candidate = Candidate::where('user_id', Auth::id())->first();
    
        if (!$candidate) {
            return response()->json([
                'message' => 'Candidate profile not found for current user.'
            ], 404);
        }
    
        // Store the uploaded resume
        $path = $request->file('resume_snapshot')->store('resumes', 'public');
    
        // Create the application
        $application = Application::create([
            'job_id' => $request->job_id,
            'candidate_id' => $candidate->id, // [FOR SORRY] Use actual candidate.id
            'contact_phone' => $request->contact_phone,
            'resume_snapshot' => $path,
            'status' => 'pending'
        ]);
    
        return response()->json([
            'message' => 'Application created',
            'application' => $application
        ]);
    }
        
    // Method: retrieve applications by candidate ID with job data
    public function getByCandidate($candidate_id)
    {
        // Validate candidate_id is an integer
        if (!is_numeric($candidate_id)) {
            return response()->json([
                'message' => 'Invalid candidate_id'
            ], 400);
        }

        // Eager load the 'job' relationship
        $applications = Application::with('job')
                                    ->where('candidate_id', $candidate_id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        return response()->json([
            'candidate_id' => (int) $candidate_id,
            'applications' => $applications
        ]);
    }

    // Method: delete an application by ID
    public function destroy($id)
    {
        // Find application by ID
        $application = Application::find($id);

        if (!$application) {
            return response()->json([
                'message' => 'Application not found'
            ], 404);
        }

        // Delete the application
        $application->delete();

        return response()->json([
            'message' => 'Application deleted successfully'
        ]);
    }
}
