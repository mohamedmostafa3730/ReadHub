<?php

use App\Http\Middleware\role;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['role' => role::class]);
    })->withProviders([
        App\Providers\ApiResponseServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $exception, $request) {

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
                            'message' => 'HTTP error',
                            'code' => $exception->getStatusCode(),
                        ],
                    ], $exception->getStatusCode());
                }

                return response()->json([
                    'success' => false,
                    'data' => null,
                    'meta' => null,
                    'errors' => [
                        'message' => 'Internal Server Error',
                        'code' => 500,
                    ],
                ], 500);
            }
        });
    })->create();