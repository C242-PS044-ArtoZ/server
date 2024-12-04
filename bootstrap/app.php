<?php

  use App\Helpers\ApiResponse;
  use Illuminate\Foundation\Application;
  use Illuminate\Http\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpKernel\Exception\HttpException;

  return Application::configure(basePath: dirname(_DIR_))
    ->withRouting(
      web: _DIR_ . '/../routes/web.php',
      api: _DIR_ . '/../routes/api.php',
      commands: _DIR_ . '/../routes/console.php',
      health: '/up',
    )
    ->withMiddleware(fn($middleware) => [])
    ->withExceptions(fn($exceptions) => $exceptions->render(fn(Exception $e, Request $request) => $request->is('api/*')
      ? ApiResponse::error(
        Response::$statusTexts[$statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR] ?? 'An unexpected error occurred.',
        $statusCode
      )
      : null
    ))
    ->create();
