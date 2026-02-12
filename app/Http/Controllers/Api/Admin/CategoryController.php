<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:categories,slug',
        ]);

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    public function index() {
        return response()->json(Category::all(), 200);
    }

    public function update(Request $request, Category $category) {
        $category->update(
            $request->only('name', 'slug')
        );

        return response()->json($category, 200);
    }

    public function destroy(Category $category) {
        $category->delete();

        return response()->noContent();
    }
}
