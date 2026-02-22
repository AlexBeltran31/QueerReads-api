<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller {

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:250',
            'author' => 'required|string|max:250',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book = Book::create([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'description' => $validated['description'] ?? null,
        ]);

        // Attach category via pivot
        $book->categories()->attach($validated['category_id']);

        return response()->json(
            $book->load('categories'),
            201
        );
    }

    public function index() {
        return response()->json(
            \App\Models\Book::with('categories')->get(),
            200
        );
    }

    public function update(Request $request, Book $book) {
        $validated = $request->validate([
            'title' => 'required|string|max:250',
            'author' => 'required|string|max:250',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book->update([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'description' => $validated['description'] ?? null,
        ]);

        $book->categories()->sync([$validated['category_id']]);

        return response()->json(
            $book->load('categories'),
            200
        );
    }

    public function destroy(Book $book) {
        $book->delete();

        return response()->noContent();
    }

    public function show(Book $book) {
        return response()->json(
            $book->load('categories'),
            200
        );
    }
}
