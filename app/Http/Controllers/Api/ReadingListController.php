<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class ReadingListController extends Controller
{
    public function index(Request $request) {
        $books = $request->user()
                         ->readingList()
                         ->with('categories')
                         ->get();

        return response()->json($books, 200);
    }

    public function store(Request $request, Book $book) {
        $user = $request->user();

        if ($user->readingList()->where('book_id', $book->id)->exists()) {
            return response()->json([
                'message' => 'Book already in reading list'
            ], 409);
        }

        $user->readingList()->attach($book->id, [
            'status' => 'to_read'
        ]);

        return response()->json([
            'message' => 'Book added to reading list'
        ], 201);
    }

    public function update(Request $request, Book $book) {
        $request->validate([
        'status' => ['required', Rule::in(['to_read', 'reading', 'finished'])],
        ]);

        $user = $request->user();

        if (!$user->readingList()->where('book_id', $book->id)->exists()) {
            return response()->json([
                'message' => 'Book not in reading list'
            ], 404);
        }

        $user->readingList()->updateExistingPivot($book->id, [
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Reading status updated'
        ], 200);
    }
}
