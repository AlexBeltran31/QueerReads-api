<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReadingListController extends Controller
{
    public function index(User $user)
    {
        $this->authorize('manageReading', $user);

        return response()->json($user->readingList, 200);
    }

    public function store(Request $request, Book $book, User $user) {
        $this->authorize('manageReading', $user);

        $validated = $request->validate([
            'status' => 'required|in:to_read,reading,finished'
        ]);

        $exists = $user->readingList()
                    ->where('book_id', $book->id)
                    ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Book already in reading list'
            ], 409);
        }

        $user->readingList()->attach($book->id, [
            'status' => $validated['status']
        ]);

        return response()->json(['message' => 'Book added'], 201);
    }

    public function update(Request $request, Book $book, User $user) {
        $this->authorize('manageReading', $user);

        $validated = $request->validate([
            'status' => 'required|in:to_read,reading,finished'
        ]);

        $exists = $user->readingList()
                    ->where('book_id', $book->id)
                    ->exists();

        if (! $exists) {
            return response()->json([
                'message' => 'Book not found in reading list'
            ], 404);
        }

        $user->readingList()->updateExistingPivot(
            $book->id,
            ['status' => $validated['status']]
        );

        return response()->json(['message' => 'Status updated'], 200);
    }

    public function destroy(Book $book, User $user) {
        $this->authorize('manageReading', $user);

        $exists = $user->readingList()
                    ->where('book_id', $book->id)
                    ->exists();

        if (! $exists) {
            return response()->json([
                'message' => 'Book not found in reading list'
            ], 404);
        }

        $user->readingList()->detach($book->id);

        return response()->noContent();
    }
}