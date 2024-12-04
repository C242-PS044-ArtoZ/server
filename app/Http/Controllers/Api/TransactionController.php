<?php

  namespace App\Http\Controllers\Api;

  use App\Helpers\ApiResponse;
  use App\Http\Controllers\Controller;
  use App\Models\Transaction;
  use App\Traits\TryCatchTrait;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpKernel\Exception\HttpException;

  class TransactionController extends Controller
  {
    use TryCatchTrait;

    public function index(): JsonResponse
    {
      return $this->executeSafely(fn() => ApiResponse::success(
        Transaction::where('user_id', Auth::id())->get(),
        'Transactions fetched successfully.',
        Response::HTTP_OK
      ));
    }

    public function store(Request $request): JsonResponse
    {
      return $this->executeSafely(fn() => ApiResponse::success(
        Transaction::create($request->only(['nominal', 'type', 'description']) + ['user_id' => Auth::id()]),
        'Transaction created successfully.',
        Response::HTTP_CREATED
      ));
    }

    public function show(Transaction $transaction): JsonResponse
    {
      return $this->executeSafely(fn() => $transaction->user_id === Auth::id()
        ? ApiResponse::success($transaction, 'Transaction fetched successfully.', Response::HTTP_OK)
        : throw new HttpException(Response::HTTP_FORBIDDEN, 'Unauthorized access.')
      );
    }

    public function update(Request $request, Transaction $transaction): JsonResponse
    {
      return $this->executeSafely(fn() => $transaction->user_id === Auth::id()
        ? ApiResponse::success(
          tap($transaction)->update($request->only(['amount', 'description'])),
          'Transaction updated successfully.',
          Response::HTTP_OK
        )
        : throw new HttpException(Response::HTTP_FORBIDDEN, 'Unauthorized access.')
      );
    }

    public function destroy(Transaction $transaction): JsonResponse
    {
      return $this->executeSafely(fn() => $transaction->user_id === Auth::id()
        ? ApiResponse::success(
          tap($transaction)->delete(),
          'Transaction deleted successfully.',
          Response::HTTP_OK
        )
        : throw new HttpException(Response::HTTP_FORBIDDEN, 'Unauthorized access.')
      );
    }
  }
