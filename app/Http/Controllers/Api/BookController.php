<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;

class BookController extends Controller
{
    public function index() {
        return BookResource::collection(Book::all());
    }

    public function show(Book $book) {
        return new BookResource($book);
    }
}
