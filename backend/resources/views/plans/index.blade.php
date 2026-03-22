@extends('layouts.app')
@section('title', 'Subscription Plans')
@section('page-title', 'Subscription Plans')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0">All Plans</h6>
    <a href="{{ route('web.plans.create') }}" class="btn btn-sm btn-success"><i class="bi bi-plus-lg"></i> Add Plan</a>
</div>

<div class="row g-3">
    @forelse($plans as $plan)
    <div class="col-md-6 col-lg-4">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="mb-0">{{ $plan->name }}</h5>
                @if($plan->is_active)
                    <span class="badge bg-success badge-status">Active</span>
                @else
                    <span class="badge bg-secondary badge-status">Inactive</span>
                @endif
            </div>
            <h3 class="text-success mb-2">Rs. {{ number_format($plan->price, 2) }}</h3>
            <p class="text-muted small mb-3">{{ $plan->description ?? 'No description' }}</p>
            <div class="d-flex justify-content-between py-1 border-bottom small">
                <span class="text-muted">Duration</span>
                <strong>{{ $plan->duration_days }} days</strong>
            </div>
            <div class="d-flex justify-content-between py-1 border-bottom small">
                <span class="text-muted">Discount</span>
                <strong>{{ $plan->discount_percentage }}%</strong>
            </div>
            <div class="d-flex justify-content-between py-1 border-bottom small">
                <span class="text-muted">Free Charging</span>
                <strong>{{ $plan->free_charging_percentage }}%</strong>
            </div>
            <div class="d-flex justify-content-between py-1 small">
                <span class="text-muted">Priority Support</span>
                <strong>{{ $plan->priority_support ? 'Yes' : 'No' }}</strong>
            </div>
            <div class="mt-3">
                <a href="{{ route('web.plans.edit', $plan) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i> Edit</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <p class="text-center text-muted py-4">No subscription plans created yet</p>
    </div>
    @endforelse
</div>

<div class="mt-3">{{ $plans->links() }}</div>
@endsection
