<?php

public function register(): void
{
    $this->renderable(function (NotFoundHttpException $e, $request) {
        return response()->json([
            'status' => 'error',
            'message' => 'Route not found'
        ], 404);
    });

    $this->renderable(function (ModelNotFoundException $e, $request) {
        return response()->json([
            'status' => 'error',
            'message' => 'Resource not found'
        ], 404);
    });

    $this->renderable(function (ValidationException $e, $request) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    });

    $this->renderable(function (ApiException $e, $request) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], $e->getCode() ?: 400);
    });
}