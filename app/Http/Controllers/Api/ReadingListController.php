<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
}
