<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories  = Category::paginate(10);

        if ($categories->isEmpty()) {
            throw new CustomException("No categories found", 404);
        }

        return response()->json($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if ($category === null) {
            throw new CustomException("This Category with $id is not found", 404);
        }

        return response()->json($category, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, int $id)
    {
        $category = Category::find($id);
        $category->update($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $category
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            throw new CustomException("Category with ID {$id} not found", 404);
        }

        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Category deleted successfully"
        ], 200);
    }
}