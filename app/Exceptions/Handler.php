<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

public function render($request, Throwable $exception)
{
    if ($request->is('api/*') || $request->expectsJson()) {

        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'data' => null,
                'meta' => null,
                'errors' => [
                    'message' => 'Resource not found',
                    'code' => 404,
                ],
            ], 404);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'data' => null,
                'meta' => null,
                'errors' => [
                    'message' => 'Unauthenticated',
                    'code' => 401,
                ],
            ], 401);
        }

        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'data' => null,
                'meta' => null,
                'errors' => [
                    'message' => 'Forbidden',
                    'code' => 403,
                ],
            ], 403);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'data' => null,
                'meta' => null,
                'errors' => [
                    'message' => 'Validation failed',
                    'fields' => $exception->errors(),
                    'code' => 422,
                ],
            ], 422);
        }

        if ($exception instanceof HttpExceptionInterface) {
            return response()->json([
                'success' => false,
                'data' => null,
                'meta' => null,
                'errors' => [
                    'message' => match ($exception->getStatusCode()) {
                        401 => 'Unauthenticated',
                        403 => 'Forbidden',
                        404 => 'Resource not found',
                        default => 'HTTP error',
                    },
                    'code' => $exception->getStatusCode(),
                ],
            ], $exception->getStatusCode());
        }

        return response()->json([
            'success' => false,
            'data' => null,
            'meta' => null,
            'errors' => [
                'message' => config('app.debug')
                    ? $exception->getMessage()
                    : 'Internal Server Error',
                'code' => 500,
            ],
        ], 500);
    }

    return parent::render($request, $exception);
}