<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    public function balance(Request $request): JsonResponse
    {
        $balance = $this->walletService->getBalance($request->user());

        return response()->json([
            'balance' => $balance,
        ]);
    }

    public function addMoney(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:100000'],
        ]);

        $transaction = $this->walletService->addFunds(
            $request->user(),
            (float) $request->amount
        );

        return response()->json([
            'message' => 'Money added successfully.',
            'transaction' => $transaction,
            'balance' => $this->walletService->getBalance($request->user()),
        ]);
    }

    public function transactions(Request $request): JsonResponse
    {
        $wallet = $this->walletService->getOrCreateWallet($request->user());

        $transactions = $wallet->transactions()
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($transactions);
    }
}
