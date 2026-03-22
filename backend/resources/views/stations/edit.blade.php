@extends('layouts.app')
@section('title', 'Edit Station')
@section('page-title', 'Edit Station: ' . $station->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="stat-card">
            <form method="POST" action="{{ route('web.stations.update', $station) }}">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Station Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $station->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Charger Type</label>
                        <select name="charger_type" class="form-select" required>
                            @foreach(['Type 1', 'Type 2', 'CCS', 'CHAdeMO'] as $type)
                                <option value="{{ $type }}" {{ old('charger_type', $station->charger_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Address</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $station->address) }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Latitude</label>
                        <input type="number" step="any" name="latitude" class="form-control" value="{{ old('latitude', $station->latitude) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Longitude</label>
                        <input type="number" step="any" name="longitude" class="form-control" value="{{ old('longitude', $station->longitude) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Power (kW)</label>
                        <input type="number" step="0.01" name="power_kw" class="form-control" value="{{ old('power_kw', $station->power_kw) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Total Ports</label>
                        <input type="number" name="total_ports" class="form-control" value="{{ old('total_ports', $station->total_ports) }}" min="1" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Available Ports</label>
                        <input type="number" name="available_ports" class="form-control" value="{{ old('available_ports', $station->available_ports) }}" min="0" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach(['active', 'inactive', 'maintenance'] as $s)
                            <option value="{{ $s }}" {{ old('status', $station->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Image URL</label>
                    <input type="url" name="image_url" class="form-control" value="{{ old('image_url', $station->image_url) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $station->description) }}</textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning"><i class="bi bi-check-lg"></i> Update Station</button>
                    <a href="{{ route('web.stations.show', $station) }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
