<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index() {
        return BookResource::collection(Book::all());
    }

    public function show(Book $book) {
        return new BookResource($book);
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
}
