<?php

namespace App\Services;

use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Carbon\Carbon;

class SubscriptionService
{
    public function __construct(
        protected WalletService $walletService,
        protected NotificationService $notificationService
    ) {}

    public function getActivePlans()
    {
        return SubscriptionPlan::where('is_active', true)->get();
    }

    public function subscribe(User $user, int $planId): UserSubscription
    {
        $plan = SubscriptionPlan::where('is_active', true)->findOrFail($planId);

        // Check for existing active subscription
        $existing = $user->activeSubscription();
        if ($existing) {
            throw new \Exception('You already have an active subscription. It expires on ' . $existing->expires_at->format('d M Y'));
        }

        // Deduct from wallet
        $this->walletService->deductFunds($user, (float) $plan->price);

        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addDays($plan->duration_days),
            'status' => 'active',
            'amount_paid' => $plan->price,
        ]);

        $this->notificationService->send(
            $user,
            'Subscription Activated',
            "Your {$plan->name} plan is now active until {$subscription->expires_at->format('d M Y')}.",
            'subscription'
        );

        return $subscription->load('plan');
    }

    public function cancelSubscription(User $user): UserSubscription
    {
        $subscription = $user->activeSubscription();
        if (! $subscription) {
            throw new \Exception('No active subscription to cancel.');
        }

        $subscription->update(['status' => 'cancelled']);

        $this->notificationService->send(
            $user,
            'Subscription Cancelled',
            "Your {$subscription->plan->name} plan has been cancelled.",
            'subscription'
        );

        return $subscription;
    }

    public function getUserSubscription(User $user): ?UserSubscription
    {
        return $user->activeSubscription()?->load('plan');
    }

    public function getSubscriptionHistory(User $user)
    {
        return UserSubscription::where('user_id', $user->id)
            ->with('plan')
            ->orderByDesc('created_at')
            ->paginate(15);
    }

    public function getDiscountPercentage(User $user): float
    {
        $subscription = $user->activeSubscription();
        return $subscription ? (float) $subscription->plan->discount_percentage : 0;
    }
}
