<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::paginate(5);
        if ($authors->isEmpty()) {
            throw new CustomException('No authors found', 200);
        }
        return response()->json($authors, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request)
    {
        $author = Author::create($request->validated());
        return response()->json($author, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $author = Author::find($id);
        if (!$author) {
            throw new CustomException('Book with ID {$id} not found', 200);
        }
        return response()->json($author, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, string $id)
    {
        $oldAuthor = Author::find($id);
        if (!$oldAuthor) {
            throw new CustomException('Book with ID {$id} not found', 200);
        }
        $oldAuthor->update($request->validated());

        return response()->json($oldAuthor, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $author = Author::find($id);
        if (!$author) {
            throw new CustomException("Author with ID {$id} not found", 404);
        }

        $author->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Author deleted successfully"
        ], 200);
    }
}