<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\User;

class ReadingListController extends Controller
{
    public function index(User $user) {
        $this->authorize('manageReading', $user);

        return response()->json($user->books, 200);
    }

    public function store(Request $request, Book $book, User $user) {
        $this->authorize('manageReading', $user);

        $validated = $request->validate([
            'status' => 'required|in:to_read,reading,finished'
        ]);

        $user->books()->syncWithoutDetaching([
            $book->id => ['status' => $validated['status']]
        ]);

        return response()->json(['message' => 'Book attached'], 201);
    }

    public function update(Request $request, Book $book, User $user) {
        $this->authorize('manageReading', $user);

        $validated = $request->validate([
            'status' => 'required|in:to_read,reading,finished'
        ]);

        $user->books()->updateExistingPivot(
            $book->id,
            ['status' => $validated['status']]
        );

        return response()->json(['message' => 'Status updated']);
    }

    public function destroy(Book $book, User $user) {
        $this->authorize('manageReading', $user);

        $user->books()->detach($book->id);

        return response()->noContent();
    }
}
