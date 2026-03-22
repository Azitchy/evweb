<?php

namespace App\Services;

use App\Models\ChargingStation;
use App\Models\IotDevice;
use App\Models\IotTelemetry;

class IotService
{
    public function registerDevice(int $stationId, string $deviceId, string $deviceName): IotDevice
    {
        return IotDevice::updateOrCreate(
            ['device_id' => $deviceId],
            [
                'charging_station_id' => $stationId,
                'device_name' => $deviceName,
                'status' => 'online',
                'last_heartbeat_at' => now(),
            ]
        );
    }

    public function heartbeat(string $deviceId, array $data): IotDevice
    {
        $device = IotDevice::where('device_id', $deviceId)->firstOrFail();

        $device->update([
            'status' => 'online',
            'current_power_kw' => $data['power_kw'] ?? $device->current_power_kw,
            'voltage' => $data['voltage'] ?? $device->voltage,
            'current_amps' => $data['current_amps'] ?? $device->current_amps,
            'temperature' => $data['temperature'] ?? $device->temperature,
            'firmware_version' => $data['firmware_version'] ?? $device->firmware_version,
            'last_heartbeat_at' => now(),
        ]);

        return $device;
    }

    public function recordTelemetry(string $deviceId, array $data): IotTelemetry
    {
        $device = IotDevice::where('device_id', $deviceId)->firstOrFail();

        return IotTelemetry::create([
            'iot_device_id' => $device->id,
            'charging_session_id' => $data['charging_session_id'] ?? null,
            'power_kw' => $data['power_kw'] ?? 0,
            'voltage' => $data['voltage'] ?? 0,
            'current_amps' => $data['current_amps'] ?? 0,
            'temperature' => $data['temperature'] ?? 0,
            'energy_kwh' => $data['energy_kwh'] ?? 0,
            'battery_percentage' => $data['battery_percentage'] ?? null,
        ]);
    }

    public function getDeviceStatus(string $deviceId): IotDevice
    {
        return IotDevice::where('device_id', $deviceId)
            ->with('chargingStation')
            ->firstOrFail();
    }

    public function getStationDevices(int $stationId)
    {
        return IotDevice::where('charging_station_id', $stationId)->get();
    }

    public function getDeviceTelemetry(string $deviceId, int $limit = 50)
    {
        $device = IotDevice::where('device_id', $deviceId)->firstOrFail();

        return IotTelemetry::where('iot_device_id', $device->id)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function updateDeviceStatus(string $deviceId, string $status): IotDevice
    {
        $device = IotDevice::where('device_id', $deviceId)->firstOrFail();
        $device->update(['status' => $status]);

        // Also update station status if all devices are offline
        $station = $device->chargingStation;
        $onlineDevices = IotDevice::where('charging_station_id', $station->id)
            ->where('status', 'online')
            ->count();

        if ($onlineDevices === 0) {
            $station->update(['status' => 'inactive']);
        } else {
            $station->update(['status' => 'active']);
        }

        return $device;
    }
}
