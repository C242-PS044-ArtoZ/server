<?php

  namespace App\Traits;

  use App\Helpers\ApiResponse;
  use Exception;
  use Illuminate\Http\JsonResponse;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpKernel\Exception\HttpException;

  trait TryCatchTrait
  {
    protected function executeSafely(
      callable $callback,
      int      $defaultErrorCode = Response::HTTP_INTERNAL_SERVER_ERROR,
      string   $defaultErrorMessage = 'An unexpected error occurred. Please try again later.'
    ): JsonResponse
    {
      try {
        return $callback();
      } catch (HttpException $e) {
        return ApiResponse::error($e->getMessage(), $e->getStatusCode());
      } catch (Exception $e) {
        return ApiResponse::error(
          $defaultErrorMessage,
          $defaultErrorCode,
          ['error' => $e->getMessage()]
        );
      }
    }
  }
