<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChargingSession;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user()->load('wallet');

        $totalCharged = ChargingSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('charged_percentage');

        $totalSpent = Transaction::where('user_id', $user->id)
            ->where('type', 'debit')
            ->sum('amount');

        $totalSessions = ChargingSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        return response()->json([
            'user' => $user,
            'stats' => [
                'total_charging_percentage' => round($totalCharged, 2),
                'total_amount_spent' => round($totalSpent, 2),
                'total_sessions' => $totalSessions,
                'wallet_balance' => (float) $user->wallet?->balance ?? 0,
            ],
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
        ]);

        $request->user()->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $request->user()->fresh()->load('wallet'),
        ]);
    }

    public function chargingHistory(Request $request): JsonResponse
    {
        $sessions = ChargingSession::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($sessions);
    }

    public function transactionHistory(Request $request): JsonResponse
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($transactions);
    }
}
