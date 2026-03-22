@extends('layouts.app')
@section('title', 'Edit Plan')
@section('page-title', 'Edit Plan: ' . $plan->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="stat-card">
            <form method="POST" action="{{ route('web.plans.update', $plan) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Plan Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $plan->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $plan->description) }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Price (Rs.)</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $plan->price) }}" min="0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Duration (Days)</label>
                        <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days', $plan->duration_days) }}" min="1" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Discount %</label>
                        <input type="number" step="0.01" name="discount_percentage" class="form-control" value="{{ old('discount_percentage', $plan->discount_percentage) }}" min="0" max="100" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Free Charging %</label>
                        <input type="number" step="0.01" name="free_charging_percentage" class="form-control" value="{{ old('free_charging_percentage', $plan->free_charging_percentage) }}" min="0" required>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="priority_support" value="1" class="form-check-input" id="priority_support" {{ old('priority_support', $plan->priority_support) ? 'checked' : '' }}>
                    <label class="form-check-label" for="priority_support">Priority Support</label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning"><i class="bi bi-check-lg"></i> Update Plan</button>
                    <a href="{{ route('web.plans.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
