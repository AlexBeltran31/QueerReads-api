<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller {

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:250',
            'author' => 'required|string|max:250',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book = Book::create($validated);

        return response()->json(
            $book->load('category'),
            201);
    }

    public function index() {
        return response()->json(Book::all(), 200);
    }

    public function update(Request $request, Book $book) {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:250',
            'author' => 'sometimes|required|string|max:250',
            'description' => 'sometimes|nullable|string',
            'categories' => 'sometimes|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $book->update(
            collect($validated)->only([
                'title',
                'author',
                'description',
            ])->toArray()
            );

            if($request->has('categories')) {
                $book->categories()->sync($request->input('categories'));
            }

        return response()->json($book, 200);
    }

    public function destroy(Book $book) {
        $book->delete();

        return response()->noContent();
    }
}
