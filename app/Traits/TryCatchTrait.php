<?php

  namespace App\Traits;

  use App\Helpers\ApiResponse;
  use Exception;
  use Illuminate\Http\JsonResponse;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpKernel\Exception\HttpException;

  trait TryCatchTrait
  {
    protected function executeSafely(callable $callback, int $defaultErrorCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
      try {
        return $callback();
      } catch (Exception $e) {
        $statusCode = $e instanceof HttpException
          ? $e->getStatusCode()
          : $defaultErrorCode;

        return ApiResponse::error(
          $this->getErrorMessage($statusCode),
          $statusCode,
          ['error' => $e->getMessage()]
        );
      }
    }

    private function getErrorMessage(int $statusCode): string
    {
      return match ($statusCode) {
        400 => 'Bad Request. Please check your input.',
        401 => 'Authentication required. Please log in.',
        403 => 'Access denied. You do not have permission.',
        404 => 'Resource not found. The requested endpoint is invalid.',
        405 => 'Method not allowed. Check the API documentation for allowed methods.',
        406 => 'Not Acceptable. The requested format is not supported.',
        409 => 'Conflict. There is a conflict with the current state of the resource.',
        410 => 'Gone. The requested resource is no longer available.',
        411 => 'Length Required. Please specify the content length.',
        412 => 'Precondition Failed. One or more conditions were not met.',
        415 => 'Unsupported Media Type. The provided media type is not supported.',
        422 => 'Unprocessable Entity. Invalid data submitted.',
        423 => 'Locked. The resource is currently locked.',
        428 => 'Precondition Required. A required condition is missing.',
        429 => 'Too many requests. Please slow down your request rate.',
        503 => 'Service Unavailable. Please try again later.',
        default => 'Unexpected server error. Please contact support.',
      };
    }
  }
