<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * Send success response.
     *
     * @param string|null $message
     * @param array|null $data
     * @return JsonResponse
     */
    public function sendSuccessResponse(string $message = null, ?array $data = null): JsonResponse
    {
        $response = [
            'success' => true,
        ];
        if (!is_null($data)) {
            $response['data'] = $data;
        }
        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response);
    }

    /**
     * Send error response.
     *
     * @param string $message
     * @param array $errors
     * @param int $statusCode
     * @return JsonResponse
     */
    public function sendErrorResponse(string $message = 'Something went wrong', array $errors = [], int $statusCode = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];
        if (count($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
