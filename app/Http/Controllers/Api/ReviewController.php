<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function store(Request $request, Book $book) {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $user = $request->user();

        $reading = $user->readingList()
                        ->where('book_id', $book->id)
                        ->where('status', 'finished')
                        ->exists();

        if (!$reading) {
            return response()->json([
                'message' => 'You must finish the book before reviewing'
            ], 403);
        }

        $alreadyReviewed = $user->reviews()
                                ->where('book_id', $book->id)
                                ->exists();

        if ($alreadyReviewed) {
            return response()->json([
                'message' => 'You have already reviewed this book'
            ], 409);
        }

        Review::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'message' => 'Review created successfully'
        ], 201);
    }

    public function index(Book $book) {
        $reviews = $book->reviews()
                        ->with('user')
                        ->latest()
                        ->get();

        return response()->json($reviews, 200);
    }

    public function destroy(Request $request, Review $review) {
        $user = $request->user();

        if ($user->role === 'admin') {
            $review->delete();

            return response()->json([
                'message' => 'Review deleted'
            ], 200);
        }

        if ($review->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted'
        ], 200);
    }
}
