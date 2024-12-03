<?php

  use App\Helpers\ApiResponse;
  use Illuminate\Foundation\Application;
  use Illuminate\Http\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpKernel\Exception\HttpException;

  return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
      web: __DIR__ . '/../routes/web.php',
      api: __DIR__ . '/../routes/api.php',
      commands: __DIR__ . '/../routes/console.php',
      health: '/up',
    )
    ->withMiddleware(fn($middleware) => $middleware->group('api', [
    ]))
    ->withExceptions(fn($exceptions) => $exceptions->render(fn(Exception $e, Request $request) => $request->is('api/*')
      ? ApiResponse::error(
        Response::$statusTexts[$statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR] ?? 'An unexpected error occurred.',
        $statusCode
      )
      : null
    ))
    ->create();
