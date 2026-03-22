<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function plans(Request $request)
    {
        $plans = SubscriptionPlan::latest()->paginate(15);
        return view('plans.index', compact('plans'));
    }

    public function createPlan()
    {
        return view('plans.create');
    }

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'free_charging_percentage' => 'required|numeric|min:0',
            'priority_support' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['priority_support'] = $request->boolean('priority_support');
        $validated['is_active'] = $request->boolean('is_active', true);

        SubscriptionPlan::create($validated);
        return redirect()->route('web.plans.index')->with('success', 'Plan created successfully.');
    }

    public function editPlan(SubscriptionPlan $plan)
    {
        return view('plans.edit', compact('plan'));
    }

    public function updatePlan(Request $request, SubscriptionPlan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'free_charging_percentage' => 'required|numeric|min:0',
            'priority_support' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['priority_support'] = $request->boolean('priority_support');
        $validated['is_active'] = $request->boolean('is_active', true);

        $plan->update($validated);
        return redirect()->route('web.plans.index')->with('success', 'Plan updated successfully.');
    }

    public function subscriptions(Request $request)
    {
        $query = UserSubscription::with(['user', 'plan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->latest()->paginate(20)->withQueryString();
        return view('subscriptions.index', compact('subscriptions'));
    }
}
