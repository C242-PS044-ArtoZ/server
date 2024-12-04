<?php

  namespace App\Http\Controllers\Api;

  use App\Helpers\ApiResponse;
  use App\Http\Controllers\Controller;
  use App\Http\Resources\UserResource;
  use App\Models\User;
  use App\Traits\TryCatchTrait;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Hash;
  use Symfony\Component\HttpFoundation\Response as ResponseAlias;
  use Symfony\Component\HttpKernel\Exception\HttpException;

  class AuthController extends Controller
  {
    use TryCatchTrait;

    public function register(Request $request): JsonResponse
    {
      return $this->executeSafely(function () use ($request) {
        $user = User::create($request->only('name', 'email', 'password'));
        return ApiResponse::success(
          new UserResource($user),
          'Registration successful. Your account has been created.',
          ResponseAlias::HTTP_CREATED
        );
      });
    }

    public function login(Request $request): JsonResponse
    {
      return $this->executeSafely(function () use ($request) {
        $user = User::where('email', $request->email)->firstOrFail();

        if (!Hash::check($request->password, $user->password)) {
          throw new HttpException(
            ResponseAlias::HTTP_UNAUTHORIZED,
            'The email or password you entered is incorrect.'
          );
        }

        $token = $user->createToken('ArtoZ-User-Login-Token')->plainTextToken;

        return ApiResponse::success(
          new UserResource($user),
          'Login successful. You are now logged in.',
          ResponseAlias::HTTP_OK,
          $token
        );
      });
    }

    public function logout(): JsonResponse
    {
      return $this->executeSafely(function () {
        $token = Auth::user()?->currentAccessToken();

        if (!$token) {
          throw new HttpException(
            ResponseAlias::HTTP_UNAUTHORIZED,
            'Your session has expired or is invalid. Please log in again to continue.'
          );
        }

        $token->delete();

        return ApiResponse::success(
          null,
          'You have successfully logged out. Thank you for using our service.',
          ResponseAlias::HTTP_OK
        );
      });
    }
  }
