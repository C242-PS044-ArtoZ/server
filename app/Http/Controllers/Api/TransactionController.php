<?php

  namespace App\Http\Controllers\Api;

  use App\Helpers\ApiResponse;
  use App\Http\Controllers\Controller;
  use App\Http\Resources\TransactionResource;
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

    public function index(Request $request): JsonResponse
    {
      return $this->executeSafely(function () use ($request) {
        $limit = $request->get('limit', 10); // Default limit to 10 if not provided

        $transactions = Transaction::where('user_id', Auth::id())
          ->orderBy('created_at', 'desc')
          ->limit($limit)
          ->get();

        return ApiResponse::success(
          TransactionResource::collection($transactions), // Gunakan resource
          'Transactions fetched successfully.',
          Response::HTTP_OK
        );
      });
    }


    public function summary(Request $request): JsonResponse
    {
      return $this->executeSafely(function () use ($request) {
        $userId = Auth::id();
        $period = $request->query('period', 'day'); // Default period is 'day'

        // Tentukan tanggal awal dan akhir berdasarkan period
        [$startDate, $endDate] = match ($period) {
          'day' => [now()->startOfDay(), now()->endOfDay()],
          'month' => [now()->startOfMonth(), now()->endOfMonth()],
          'year' => [now()->startOfYear(), now()->endOfYear()],
          default => [now()->startOfDay(), now()->endOfDay()],
        };

        // Total income dan expense berdasarkan periode
        $incomeTransactions = Transaction::where('user_id', $userId)
          ->where('type', 'income')
          ->whereBetween('created_at', [$startDate, $endDate])
          ->sum('nominal');

        $expenseTransactions = Transaction::where('user_id', $userId)
          ->where('type', 'expense')
          ->whereBetween('created_at', [$startDate, $endDate])
          ->sum('nominal');

        // Total balance dihitung dari seluruh data tanpa batasan waktu
        $totalIncome = Transaction::where('user_id', $userId)
          ->where('type', 'income')
          ->sum('nominal');

        $totalExpense = Transaction::where('user_id', $userId)
          ->where('type', 'expense')
          ->sum('nominal');

        $balance = $totalIncome - $totalExpense;

        return ApiResponse::success(
          [
            'balance' => $balance, // Selalu dihitung dari seluruh data
            'total_income' => $incomeTransactions, // Berdasarkan periode
            'total_expense' => $expenseTransactions, // Berdasarkan periode
            'period' => $period,
          ],
          'Summary fetched successfully.',
          Response::HTTP_OK
        );
      });
    }

    public function store(Request $request): JsonResponse
    {
      return $this->executeSafely(fn() => ApiResponse::success(
        new TransactionResource(Transaction::create(
          $request->only(['nominal', 'type', 'description']) + ['user_id' => Auth::id()]
        )),
        'Transaction created successfully.',
        Response::HTTP_CREATED
      ));
    }


    public function show(Transaction $transaction): JsonResponse
    {
      return $this->executeSafely(fn() => $transaction->user_id === Auth::id()
        ? ApiResponse::success(
          new TransactionResource($transaction), // Gunakan resource
          'Transaction fetched successfully.',
          Response::HTTP_OK
        )
        : throw new HttpException(Response::HTTP_FORBIDDEN, 'Unauthorized access.')
      );
    }


    public function update(Request $request, Transaction $transaction): JsonResponse
    {
      return $this->executeSafely(fn() => $transaction->user_id === Auth::id()
        ? ApiResponse::success(
          new TransactionResource(tap($transaction)->update($request->only(['nominal', 'description']))),
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
          new TransactionResource(tap($transaction)->delete()), // Gunakan resource
          'Transaction deleted successfully.',
          Response::HTTP_OK
        )
        : throw new HttpException(Response::HTTP_FORBIDDEN, 'Unauthorized access.')
      );
    }

  }
