<?php

  namespace App\Http\Controllers\Api;

  use App\Helpers\ApiResponse;
  use App\Http\Controllers\Controller;
  use App\Http\Requests\RegisterRequest;
  use App\Http\Resources\UserResource;
  use App\Models\User;
  use Exception;
  use Illuminate\Http\JsonResponse;
  use Symfony\Component\HttpFoundation\Response as ResponseAlias;

  class AuthController extends Controller
  {
    public function register(RegisterRequest $request): JsonResponse
    {
      try {
        $user = User::create($request->only('name', 'email', 'password'));
        return ApiResponse::success(new UserResource($user), 'Account successfully registered.', ResponseAlias::HTTP_CREATED);
      } catch (Exception $e) {
        return ApiResponse::error('Account registration failed.', ResponseAlias::HTTP_INTERNAL_SERVER_ERROR, ['error' => $e->getMessage()]);
      }
    }
  }
