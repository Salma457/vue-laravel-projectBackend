<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usersjob;
class AdminController extends Controller
{
     public function index()
    {
        return Usersjob::with('employer')->orderBy('created_at', 'desc')->get();
    }

    public function approve($id)
    {
        $job = Usersjob::findOrFail($id);
        $job->status = 'approved';
        $job->save();

        return response()->json(['message' => 'Job approved']);
    }

    public function reject($id)
    {
        $job = Usersjob::findOrFail($id);
        $job->status = 'rejected';
        $job->save();

        return response()->json(['message' => 'Job rejected']);
    }
}



