@extends('layouts.app')
@section('title', 'Create Plan')
@section('page-title', 'Create Subscription Plan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="stat-card">
            <form method="POST" action="{{ route('web.plans.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Plan Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Price (Rs.)</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" min="0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Duration (Days)</label>
                        <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days', 30) }}" min="1" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Discount %</label>
                        <input type="number" step="0.01" name="discount_percentage" class="form-control" value="{{ old('discount_percentage', 0) }}" min="0" max="100" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Free Charging %</label>
                        <input type="number" step="0.01" name="free_charging_percentage" class="form-control" value="{{ old('free_charging_percentage', 0) }}" min="0" required>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="priority_support" value="1" class="form-check-input" id="priority_support" {{ old('priority_support') ? 'checked' : '' }}>
                    <label class="form-check-label" for="priority_support">Priority Support</label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Create Plan</button>
                    <a href="{{ route('web.plans.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
