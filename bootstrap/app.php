<?php

  use App\Helpers\ApiResponse;
  use Illuminate\Foundation\Application;
  use Illuminate\Http\Request;
  use Symfony\Component\HttpKernel\Exception\HttpException;

  return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
      web: __DIR__ . '/../routes/web.php',
      api: __DIR__ . '/../routes/api.php',
      commands: __DIR__ . '/../routes/console.php',
      health: '/up',
    )
    ->withMiddleware(fn($middleware) => $middleware->group('api', []))
    ->withExceptions(fn($exceptions) => $exceptions->render(fn(Exception $e, Request $request) => $request->is('api/*')
      ? ApiResponse::error(
        match (($e instanceof HttpException) ? $e->getStatusCode() : 500) {
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
        },
        ($e instanceof HttpException) ? $e->getStatusCode() : 500
      )
      : null
    ))
    ->create();
