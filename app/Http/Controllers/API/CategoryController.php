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
public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_icon' => 'nullable|string|max:255',
            'category_description' => 'nullable|string',
        ]);

        $category = Category::create($validatedData);
        return response()->json($category, 201);
    }



}
