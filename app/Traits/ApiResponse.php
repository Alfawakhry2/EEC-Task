<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Success response
     *
     * @param int $status_code
     * @param string $message
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($status_code, $message, $data = null)
    {
        return response()->json([
            'status_code' => $status_code,
            'message' => $message,
            'data' => $data,
            'error' => null,
        ], $status_code);
    }

    /**
     * Error response
     *
     * @param int $status_code
     * @param string $message
     * @param mixed $error
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($status_code, $message, $error = null)
    {
        return response()->json([
            'status_code' => $status_code,
            'message' => $message,
            'data' => null,
            'error' => $error,
        ], $status_code);
    }
}