<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\JsonResponse;


class CategoryController extends Controller
{
    public function index(): JsonResponse
{
    $categories = Category::all();
    return response()->json($categories);
}
}
