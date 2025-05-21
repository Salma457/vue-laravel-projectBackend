<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// import candidate model
use App\Models\Candidate;

class CandidateController extends Controller
{

    public function index(){
        $candidates = Candidate::all();
        return $candidates;
    }
  public function show($id)
{
    $candidate = Candidate::find($id);

    if (!$candidate) {
        return response()->json(['message' => 'Candidate not found'], 404);
    }

    return response()->json($candidate);
}

    public function store(Request $request) {

        // validation
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'resume' => 'nullable|string',
            'linkedin_profile' => 'nullable|string',
            'phone_number' => 'required|string',
            'experience_level' => 'required|string',
            'location' => 'required|string',
        ]);

        // create candidate
        $candidate = Candidate::create($validated);

        // send feedback
        return response()->json(['candidate' => $candidate], 201);
    }

}
