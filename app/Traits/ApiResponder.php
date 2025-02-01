<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponder
{
    /**
     * Base response for API
     * @param bool $success
     * @param mixed $data
     * @param string $message
     * @param mixed $errors
     * @param int $code
     *
     * @return JsonResponse
     */
    private static function baseResponse(
        bool $success = true,
        mixed $data = null,
        string $message = '',
        mixed $errors = [],
        int $code = 200
    ): JsonResponse {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    protected static function apiSuccess(
        mixed $data = [],
        string $message = 'Success',
        int $code = 200
    ): JsonResponse {
        return self::baseResponse(success: true, message: $message, data: $data, code: $code);
    }

    protected static function apiError(
        string $message = 'Error',
        mixed $errors = [],
        int $code = 400
    ): JsonResponse {
        return self::baseResponse(success: false, message: $message, errors: $errors, code: $code);
    }

    protected static function apiCreated(
        mixed $data = [],
        string $message = 'Created'
    ): JsonResponse {
        return self::baseResponse(success: true, message: $message, data: $data, code: 201);
    }
}
