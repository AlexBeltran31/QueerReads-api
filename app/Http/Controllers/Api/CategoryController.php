<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() {
        return response()->json(Category::all(), 200);
    }

    public function books(Category $category) {
        return response()->json(
            $category->books,
            200
        );
    }
}
