<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\IotDevice;
use App\Models\ChargingStation;
use Illuminate\Http\Request;

class IotController extends Controller
{
    public function index(Request $request)
    {
        $query = IotDevice::with('chargingStation');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('device_id', 'like', "%{$search}%")
                  ->orWhere('device_name', 'like', "%{$search}%");
            });
        }

        $devices = $query->latest()->paginate(20)->withQueryString();
        return view('iot.index', compact('devices'));
    }

    public function show(IotDevice $device)
    {
        $device->load(['chargingStation', 'telemetry' => fn($q) => $q->latest()->take(50)]);
        return view('iot.show', compact('device'));
    }

    public function create()
    {
        $stations = ChargingStation::orderBy('name')->get();
        return view('iot.create', compact('stations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'charging_station_id' => 'required|exists:charging_stations,id',
            'device_id' => 'required|string|unique:iot_devices,device_id',
            'device_name' => 'required|string|max:255',
            'status' => 'required|in:online,offline,maintenance,error',
            'firmware_version' => 'nullable|string|max:50',
        ]);

        IotDevice::create($validated);
        return redirect()->route('web.iot.index')->with('success', 'Device registered successfully.');
    }

    public function edit(IotDevice $device)
    {
        $stations = ChargingStation::orderBy('name')->get();
        return view('iot.edit', compact('device', 'stations'));
    }

    public function update(Request $request, IotDevice $device)
    {
        $validated = $request->validate([
            'charging_station_id' => 'required|exists:charging_stations,id',
            'device_id' => 'required|string|unique:iot_devices,device_id,' . $device->id,
            'device_name' => 'required|string|max:255',
            'status' => 'required|in:online,offline,maintenance,error',
            'firmware_version' => 'nullable|string|max:50',
        ]);

        $device->update($validated);
        return redirect()->route('web.iot.show', $device)->with('success', 'Device updated successfully.');
    }
}
