<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::paginate(10);

        if ($books->isEmpty()) {
            throw new CustomException("No books found", 404);
        }

        return response()->json($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $data =  Book::create($request->validated());
        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::find($id);

        if ($book === null) {
            throw new CustomException("This book with $id is not found", 404);
        }

        return response()->json($book,201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, string $id)
    {
        $oldbook = Book::find($id);

        if (!$oldbook) {
            throw new CustomException("Book with ID {$id} not found", 404);
        }
        $oldbook->update($request->validated());
        return response()->json($oldbook, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);
        if (!$book) {
            throw new CustomException("Book with ID {$id} not found", 404);
        }
        
        $book->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Book deleted successfully"
        ], 200);
    }
}