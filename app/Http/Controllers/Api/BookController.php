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
