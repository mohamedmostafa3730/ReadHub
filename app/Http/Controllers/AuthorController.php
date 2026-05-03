<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\Author\UserResource as ResourcesAuthorUserResource;
use App\Http\Resources\Author\UsersResource as AuthorUserResource;
use App\Http\Resources\UserResource;
use App\Models\Author;
use App\support\ApiResponseBuilder;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // with builder design pattern
    // public function index()
    // {
    //     $authors = Author::all();
    //     if ($authors != null) {
    //         return ApiResponseBuilder::success()
    //             ->data(UserResource::collection($authors))
    //             ->status(200)
    //             ->build();
    //     }
    //     return ApiResponseBuilder::error('data not fond')
    //         ->status(404)
    //         ->build();
    // }

    // with Response Macro
    public function index()
    {
        $authors = Author::paginate(5);

        return response()->api([
            'data' => AuthorUserResource::collection($authors->items()),
            'meta' => [
                'current_page' => $authors->currentPage(),
                'last_page' => $authors->lastPage(),
                'total' => $authors->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request)
    {
        $author = Author::create($request->validated());
        return response()->api([
            'data' => new AuthorUserResource($author)
        ]);
        // return response()->json($author, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $author = Author::findOrFail($id);
        return response()->api([
            'data' =>  new AuthorUserResource($author),
        ]);
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