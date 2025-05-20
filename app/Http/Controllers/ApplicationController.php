<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    // Method: storing application
    public function store(Request $request)
    {
        // Add application
        $application = Application::create($request->all());

        // Feedback
        return response()->json([
            'message' => 'application created',
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
