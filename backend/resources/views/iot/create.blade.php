@extends('layouts.app')
@section('title', 'Register Device')
@section('page-title', 'Register New IoT Device')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="stat-card">
            <form method="POST" action="{{ route('web.iot.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Charging Station</label>
                    <select name="charging_station_id" class="form-select" required>
                        <option value="">Select station...</option>
                        @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ old('charging_station_id') == $station->id ? 'selected' : '' }}>
                                {{ $station->name }} ({{ $station->address }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Device ID</label>
                    <input type="text" name="device_id" class="form-control" value="{{ old('device_id') }}" required placeholder="e.g. IOT-001">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Device Name</label>
                    <input type="text" name="device_name" class="form-control" value="{{ old('device_name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="offline" {{ old('status') === 'offline' ? 'selected' : '' }}>Offline</option>
                        <option value="online" {{ old('status') === 'online' ? 'selected' : '' }}>Online</option>
                        <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Firmware Version</label>
                    <input type="text" name="firmware_version" class="form-control" value="{{ old('firmware_version') }}" placeholder="e.g. v1.0.0">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Register Device</button>
                    <a href="{{ route('web.iot.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
