<?php

namespace App\Services;

use App\Models\ChargingSession;
use App\Models\PricingSetting;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChargingService
{
    public function __construct(
        protected WalletService $walletService,
        protected SubscriptionService $subscriptionService
    ) {}

    public function startCharging(User $user, float $startPercentage, ?int $stationId = null): ChargingSession
    {
        $activeSession = ChargingSession::where('user_id', $user->id)
            ->where('status', 'charging')
            ->first();

        if ($activeSession) {
            throw new \Exception('You already have an active charging session.');
        }

        $pricePerPercentage = PricingSetting::getCurrentPrice();
        if ($pricePerPercentage <= 0) {
            throw new \Exception('Charging pricing is not configured.');
        }

        return ChargingSession::create([
            'user_id' => $user->id,
            'charging_station_id' => $stationId,
            'start_percentage' => $startPercentage,
            'price_per_percentage' => $pricePerPercentage,
            'status' => 'charging',
            'started_at' => now(),
        ]);
    }

    public function stopCharging(User $user, int $sessionId, float $endPercentage): ChargingSession
    {
        return DB::transaction(function () use ($user, $sessionId, $endPercentage) {
            $session = ChargingSession::where('id', $sessionId)
                ->where('user_id', $user->id)
                ->where('status', 'charging')
                ->lockForUpdate()
                ->firstOrFail();

            if ($endPercentage < $session->start_percentage) {
                throw new \Exception('End percentage cannot be less than start percentage.');
            }

            $chargedPercentage = $endPercentage - $session->start_percentage;
            $cost = $chargedPercentage * $session->price_per_percentage;

            // Apply subscription discount
            $discount = $this->subscriptionService->getDiscountPercentage($user);
            if ($discount > 0) {
                $cost = $cost * (1 - $discount / 100);
            }

            $session->update([
                'end_percentage' => $endPercentage,
                'charged_percentage' => $chargedPercentage,
                'cost' => round($cost, 2),
                'status' => 'completed',
                'ended_at' => now(),
            ]);

            if ($cost > 0) {
                $this->walletService->deductFunds($user, round($cost, 2), $session->id);
            }

            return $session->fresh();
        });
    }

    public function getActiveSession(User $user): ?ChargingSession
    {
        return ChargingSession::where('user_id', $user->id)
            ->where('status', 'charging')
            ->first();
    }

    public function getUserHistory(User $user, int $perPage = 15)
    {
        return ChargingSession::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
