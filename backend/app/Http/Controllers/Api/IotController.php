<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\IotService;
use Illuminate\Http\Request;

class IotController extends Controller
{
    public function __construct(
        protected IotService $iotService
    ) {}

    public function register(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:charging_stations,id',
            'device_id' => 'required|string|max:100',
            'device_name' => 'required|string|max:255',
        ]);

        $device = $this->iotService->registerDevice(
            $request->station_id,
            $request->device_id,
            $request->device_name
        );

        return response()->json([
            'message' => 'Device registered',
            'data' => $device,
        ]);
    }

    public function heartbeat(Request $request, string $deviceId)
    {
        $request->validate([
            'power_kw' => 'nullable|numeric',
            'voltage' => 'nullable|numeric',
            'current_amps' => 'nullable|numeric',
            'temperature' => 'nullable|numeric',
            'firmware_version' => 'nullable|string',
        ]);

        try {
            $device = $this->iotService->heartbeat($deviceId, $request->all());

            return response()->json(['message' => 'Heartbeat received', 'data' => $device]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function telemetry(Request $request, string $deviceId)
    {
        $request->validate([
            'charging_session_id' => 'nullable|exists:charging_sessions,id',
            'power_kw' => 'required|numeric',
            'voltage' => 'required|numeric',
            'current_amps' => 'required|numeric',
            'temperature' => 'nullable|numeric',
            'energy_kwh' => 'nullable|numeric',
            'battery_percentage' => 'nullable|numeric|between:0,100',
        ]);

        try {
            $telemetry = $this->iotService->recordTelemetry($deviceId, $request->all());

            return response()->json(['message' => 'Telemetry recorded', 'data' => $telemetry]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function status(string $deviceId)
    {
        try {
            $device = $this->iotService->getDeviceStatus($deviceId);

            return response()->json($device);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function stationDevices(int $stationId)
    {
        return response()->json($this->iotService->getStationDevices($stationId));
    }

    public function deviceTelemetry(string $deviceId)
    {
        try {
            return response()->json($this->iotService->getDeviceTelemetry($deviceId));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function updateStatus(Request $request, string $deviceId)
    {
        $request->validate([
            'status' => 'required|in:online,offline,maintenance,error',
        ]);

        try {
            $device = $this->iotService->updateDeviceStatus($deviceId, $request->status);

            return response()->json(['message' => 'Status updated', 'data' => $device]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
