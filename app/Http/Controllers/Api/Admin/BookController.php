<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller {
    public function index() {
        return response()->json(Book::all(), 200);
    }
    
    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:250',
            'author' => 'required|string|max:250',
            'description' => 'nullable|string',
        ]);

        $book = Book::create($validated);

        return response()->json($book, 201);
    }
}
