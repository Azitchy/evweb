<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    public function plans()
    {
        return response()->json($this->subscriptionService->getActivePlans());
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        try {
            $subscription = $this->subscriptionService->subscribe(
                $request->user(),
                $request->plan_id
            );

            return response()->json([
                'message' => 'Subscribed successfully',
                'data' => $subscription->load('subscriptionPlan'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function current(Request $request)
    {
        $subscription = $this->subscriptionService->getUserSubscription($request->user());

        return response()->json($subscription);
    }

    public function cancel(Request $request)
    {
        try {
            $this->subscriptionService->cancelSubscription($request->user());

            return response()->json(['message' => 'Subscription cancelled']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function history(Request $request)
    {
        $history = $request->user()
            ->subscriptions()
            ->with('subscriptionPlan')
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($history);
    }
}
