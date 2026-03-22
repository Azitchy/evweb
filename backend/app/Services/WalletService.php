<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function getOrCreateWallet(User $user): Wallet
    {
        return $user->wallet ?? Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);
    }

    public function addFunds(User $user, float $amount): Transaction
    {
        return DB::transaction(function () use ($user, $amount) {
            $wallet = $this->getOrCreateWallet($user);
            $wallet->lockForUpdate();
            $wallet->balance += $amount;
            $wallet->save();

            return Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'credit',
                'amount' => $amount,
                'balance_after' => $wallet->balance,
                'description' => 'Wallet top-up',
            ]);
        });
    }

    public function deductFunds(User $user, float $amount, ?int $chargingSessionId = null): Transaction
    {
        return DB::transaction(function () use ($user, $amount, $chargingSessionId) {
            $wallet = $this->getOrCreateWallet($user);
            $wallet->lockForUpdate();

            if ($wallet->balance < $amount) {
                throw new \Exception('Insufficient wallet balance.');
            }

            $wallet->balance -= $amount;
            $wallet->save();

            return Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'charging_session_id' => $chargingSessionId,
                'type' => 'debit',
                'amount' => $amount,
                'balance_after' => $wallet->balance,
                'description' => $chargingSessionId
                    ? 'Charging session payment'
                    : 'Wallet deduction',
            ]);
        });
    }

    public function getBalance(User $user): float
    {
        $wallet = $this->getOrCreateWallet($user);
        return (float) $wallet->balance;
    }
}
