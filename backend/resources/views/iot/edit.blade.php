@extends('layouts.app')
@section('title', 'Edit Device')
@section('page-title', 'Edit Device: ' . $device->device_name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="stat-card">
            <form method="POST" action="{{ route('web.iot.update', $device) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Charging Station</label>
                    <select name="charging_station_id" class="form-select" required>
                        @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ old('charging_station_id', $device->charging_station_id) == $station->id ? 'selected' : '' }}>
                                {{ $station->name }} ({{ $station->address }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Device ID</label>
                    <input type="text" name="device_id" class="form-control" value="{{ old('device_id', $device->device_id) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Device Name</label>
                    <input type="text" name="device_name" class="form-control" value="{{ old('device_name', $device->device_name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach(['online', 'offline', 'maintenance', 'error'] as $s)
                            <option value="{{ $s }}" {{ old('status', $device->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Firmware Version</label>
                    <input type="text" name="firmware_version" class="form-control" value="{{ old('firmware_version', $device->firmware_version) }}">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning"><i class="bi bi-check-lg"></i> Update Device</button>
                    <a href="{{ route('web.iot.show', $device) }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
