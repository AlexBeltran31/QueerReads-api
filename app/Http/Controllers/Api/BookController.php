<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index(Request $request) {
        $query = Book::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('author', 'like', '%' . $request->search . '%');
            });
        }

        return $query->with('categories')->get();
    }

    public function show(Book $book) {
        return response()->json(
            $book->load('categories'),
            200
        );
    }

    public function randomToRead(Request $request) {
        $user = $request->user();

        $book = $user->readingList()
                     ->wherePivot('status', 'to_read')
                     ->inRandomOrder()
                     ->first();

        if (!$book) {
            return response()->json([
                'message' => 'No books to read'
            ], 404);
        }

        return response()->json($book, 200);
    }

    public function store(Request $request) {
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

        $book->categories()->attach($validated['category_id']);

        return response()->json(
            $book->load('categories'),
            201
        );
    }

    public function update(Request $request, Book $book) {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book->update($validated);

        if ($request->has('categories')) {
            $book->categories()->sync($request->categories);
        }

        return response()->json($book->load('categories'), 200);
    }

    public function destroy(Book $book) {
        $book->categories()->detach();
        $book->users()->detach();

        $book->reviews()->delete();

        $book->delete();

        return response()->noContent();
    }
}
