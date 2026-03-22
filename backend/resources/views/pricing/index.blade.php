@extends('layouts.app')
@section('title', 'Pricing')
@section('page-title', 'Pricing Settings')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="stat-card mb-3">
            <h6 class="mb-3">Current Active Price</h6>
            @if($currentPrice)
                <h2 class="text-success mb-1">Rs. {{ number_format($currentPrice->price_per_percentage, 2) }}</h2>
                <span class="text-muted">per percentage point</span>
                <p class="mt-2 small text-muted">Set on {{ $currentPrice->created_at->format('M d, Y H:i') }}</p>
            @else
                <p class="text-muted">No active pricing set</p>
            @endif
        </div>

        <div class="stat-card">
            <h6 class="mb-3">Update Pricing</h6>
            <form method="POST" action="{{ route('web.pricing.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Price Per Percentage (Rs.)</label>
                    <input type="number" step="0.01" name="price_per_percentage" class="form-control" min="0.01" required placeholder="e.g. 2.50">
                </div>
                <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-lg"></i> Set New Price</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="table-card">
            <div class="card-header">Pricing History</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Price Per %</th>
                            <th>Status</th>
                            <th>Set On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pricings as $pricing)
                        <tr>
                            <td>{{ $pricing->id }}</td>
                            <td>Rs. {{ number_format($pricing->price_per_percentage, 2) }}</td>
                            <td>
                                @if($pricing->is_active)
                                    <span class="badge bg-success badge-status">Active</span>
                                @else
                                    <span class="badge bg-secondary badge-status">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $pricing->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No pricing records</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $pricings->links() }}</div>
    </div>
</div>
@endsection
