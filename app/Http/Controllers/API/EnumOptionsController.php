<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EnumOptionsController extends Controller
{
     public function jobOptions(): JsonResponse
    {
        return response()->json([
            'work_types' => ['full-time', 'part-time', 'remote', 'hybrid'],
        ]);
    }

}
