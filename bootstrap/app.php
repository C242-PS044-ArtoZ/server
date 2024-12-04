<?php

  use Illuminate\Foundation\Application;
  use Illuminate\Http\Request;
  use Symfony\Component\HttpFoundation\Response;

  return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
      web: __DIR__ . '/../routes/web.php',
      api: __DIR__ . '/../routes/api.php',
      commands: __DIR__ . '/../routes/console.php',
      health: '/up',
    )
    ->withMiddleware(fn($middleware) => [])
    ->withExceptions(fn($exceptions) => $exceptions->render(function (Throwable $e, Request $request) {
      $statusCode = method_exists($e, 'getStatusCode')
        ? $e->getStatusCode()
        : Response::HTTP_INTERNAL_SERVER_ERROR;

      $errorMessage = $e->getMessage() ?: 'An unexpected error occurred.';

      return $request->is('api/*')
        ? response()->json([
          'success' => false,
          'message' => $errorMessage,
          'code' => $statusCode,
          'trace' => env('APP_DEBUG') ? $e->getTrace() : null,
        ], $statusCode)
        : null;
    }))
    ->create();
