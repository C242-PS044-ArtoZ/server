<?php

  namespace App\Helpers;

  use Illuminate\Http\JsonResponse;

  class ApiResponse
  {
    public static function success(mixed $data = null, string $message = '', int $code = 200, string $token = null): JsonResponse
    {
      $additional = array_filter(compact('data', 'token'));
      return self::formatResponse('success', $message, $code, $additional);
    }

    private static function formatResponse(string $status, string $message, int $code, array $additional = []): JsonResponse
    {
      return response()->json(array_merge([
        'status' => $status,
        'message' => $message,
        'code' => $code,
      ], $additional), $code);
    }

    public static function error(string $message, int $code = 400, array $errors = null): JsonResponse
    {
      $additional = array_filter(['errors' => $errors]);
      return self::formatResponse('error', $message, $code, $additional);
    }
  }
