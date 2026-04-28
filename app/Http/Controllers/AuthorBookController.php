<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Author;
use App\Models\Book;

class AuthorBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Author $author)
    {
        $books = $author->books()->paginate(5);
        if ($books->isEmpty()) {
            throw new CustomException('The Author with {$author->id)}', 404);
        }

        return response()->json($books,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request, Author $author)
    {
        $data = Book::create($request->all());
        $author->books()->attach($data->id);
        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author, Book $book)
    {
        if (!$author->books->contains($book->id)) {
            return response()->json(['error' => 'Book not found for this author'], 400);
        }
        return response()->json($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Author $author, Book $book)
    {
        if (!$author->books->contains($book->id)) {
            return response()->json(['error' => 'Not related'], 404);
        }
        $data = $request->all();
        $book->update($data);
        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author, Book $book)
    {
        if (!$author->books->contains($book->id)) {
            return response()->json(['error' => 'Not related'], 404);
        }

        $author->books()->detach($book->id);

        $book->delete();

        return response()->json(['message' => 'Deleted']);
    }
}