<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usersjob;
use Illuminate\Support\Facades\Auth;

class UsersjobController extends Controller
{
  
    public function getAllJobs()
    {
        $jobs = Usersjob::with(['category', 'employer'])->get();
        return response()->json($jobs);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);
        $job = Usersjob::findOrFail($id);
        $job->status = $request->status;
        $job->save();

        return response()->json([
            'message' => 'Job status updated successfully',
            'job' => $job
        ]);
    }
 //search
public function search(Request $request)
{
      $query = $request->input('query');
    $jobs = Usersjob::where('status', 'approved')
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', '%' . $query . '%')
                      ->orWhere('location', 'like', '%' . $query . '%')
                      ->orWhere('description', 'like', '%' . $query . '%')
                      ->orWhere('responsibilities', 'like', '%' . $query . '%')
                      ->orWhere('benefits', 'like', '%' . $query . '%');
                })
                ->get();
           return response()->json($jobs);
}
    public function index()
    {
        $employer = Auth::user()->employer;

        $jobs = $employer->jobs()->with('category')->get();
        return response()->json($jobs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'work_type' => 'required|string',
            'location' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'salary_from' => 'nullable|numeric',
            'salary_to' => 'nullable|numeric',
            'deadline' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,approved,rejected',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
        ]);

        $validated['employer_id'] = Auth::user()->employer->id;

        $job = Usersjob::create($validated);

        return response()->json($job, 201);
    }

    public function show($id)
    {
        $job = Usersjob::with('category')->findOrFail($id);

        if ($job->employer_id != Auth::user()->employer->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($job);
    }

    public function update(Request $request, $id)
    {
        $job = Usersjob::findOrFail($id);

        if ($job->employer_id != Auth::user()->employer->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string',
            'work_type' => 'sometimes|required|string',
            'location' => 'nullable|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'salary_from' => 'nullable|numeric',
            'salary_to' => 'nullable|numeric',
            'deadline' => 'sometimes|required|date',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,approved,rejected',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
        ]);

        $job->update($validated);

        return response()->json($job);
    }

    public function destroy($id)
    {
        $job = Usersjob::findOrFail($id);

        if ($job->employer_id != Auth::user()->employer->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $job->delete();

        return response()->json(['message' => 'Job deleted successfully']);
    }




    // SENU: ADDING THE SEARCHING JOBS FOR THE JOB PAGE ::::
    public function searchJobs(Request $request)
{
    $title = $request->query('title');
    $location = $request->query('location');

    $query = Usersjob::query();

    if ($title) {
        $query->where('title', 'LIKE', '%' . $title . '%');
    }

    if ($location) {
        $query->where('location', 'LIKE', '%' . $location . '%');
    }

    $jobs = $query->get();

    return response()->json($jobs);
}

}
