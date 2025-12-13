<?php
namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class Common
{
    const ADMIN = 'admin';
    /**
     * Common helper methods for API responses.
     */
    public static function successResponse($message, array $data, $statusCode = 200) : JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $statusCode,
        ], $statusCode);
    }

    public static function errorResponse($message, $errors, $statusCode = 500) : JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
            'status' => $statusCode,
        ], $statusCode);
    }
}
