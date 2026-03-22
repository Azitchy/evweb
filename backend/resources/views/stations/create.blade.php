@extends('layouts.app')
@section('title', 'Create Station')
@section('page-title', 'Create New Station')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="stat-card">
            <form method="POST" action="{{ route('web.stations.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Station Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Charger Type</label>
                        <select name="charger_type" class="form-select" required>
                            <option value="Type 1" {{ old('charger_type') === 'Type 1' ? 'selected' : '' }}>Type 1</option>
                            <option value="Type 2" {{ old('charger_type') === 'Type 2' ? 'selected' : '' }}>Type 2</option>
                            <option value="CCS" {{ old('charger_type') === 'CCS' ? 'selected' : '' }}>CCS</option>
                            <option value="CHAdeMO" {{ old('charger_type') === 'CHAdeMO' ? 'selected' : '' }}>CHAdeMO</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Address</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Latitude</label>
                        <input type="number" step="any" name="latitude" class="form-control" value="{{ old('latitude') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Longitude</label>
                        <input type="number" step="any" name="longitude" class="form-control" value="{{ old('longitude') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Power (kW)</label>
                        <input type="number" step="0.01" name="power_kw" class="form-control" value="{{ old('power_kw') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Total Ports</label>
                        <input type="number" name="total_ports" class="form-control" value="{{ old('total_ports', 1) }}" min="1" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Available Ports</label>
                        <input type="number" name="available_ports" class="form-control" value="{{ old('available_ports', 1) }}" min="0" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Image URL</label>
                    <input type="url" name="image_url" class="form-control" value="{{ old('image_url') }}" placeholder="https://...">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Create Station</button>
                    <a href="{{ route('web.stations.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
