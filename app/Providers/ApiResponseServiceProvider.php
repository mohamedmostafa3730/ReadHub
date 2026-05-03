<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ApiResponseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Response::macro('api', function ($data = null, $meta = null, $status = 200) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => $meta,
                'errors' => null,
            ], $status);
        });

        Response::macro('apiError', function ($message = 'Error', $status = 400, $details = null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'meta' => null,
                'errors' => [
                    'message' => $message,
                    'details' => $details,
                    'code' => $status,
                ],
            ], $status);
        });
    }
}