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

    public function store(Request $request, Book $book, User $user)
    {
        $this->authorize('manageReading', $user);

        $validated = $request->validate([
            'status' => 'required|in:to_read,reading,finished'
        ]);

        $user->readingList()->syncWithoutDetaching([
            $book->id => ['status' => $validated['status']]
        ]);

        return response()->json(['message' => 'Book added'], 201);
    }

    public function update(Request $request, Book $book, User $user)
    {
        $this->authorize('manageReading', $user);

        $validated = $request->validate([
            'status' => 'required|in:to_read,reading,finished'
        ]);

        $user->readingList()->updateExistingPivot(
            $book->id,
            ['status' => $validated['status']]
        );

        return response()->json(['message' => 'Status updated']);
    }

    public function destroy(Book $book, User $user)
    {
        $this->authorize('manageReading', $user);

        $user->readingList()->detach($book->id);

        return response()->noContent();
    }
}