<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function store(Request $request) {
        $category = Category::create($request->only('name', 'slug'));

        return response()->json($category, 201);
    }
}
