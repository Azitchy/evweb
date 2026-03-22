<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ChargingStation;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index(Request $request)
    {
        $query = ChargingStation::withCount(['chargingSessions', 'iotDevices']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('charger_type')) {
            $query->where('charger_type', $request->charger_type);
        }

        $stations = $query->latest()->paginate(15)->withQueryString();
        return view('stations.index', compact('stations'));
    }

    public function show(ChargingStation $station)
    {
        $station->load(['iotDevices', 'chargingSessions.user']);
        $recentSessions = $station->chargingSessions()->with('user')->latest()->take(20)->get();
        return view('stations.show', compact('station', 'recentSessions'));
    }

    public function create()
    {
        return view('stations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'status' => 'required|in:active,inactive,maintenance',
            'total_ports' => 'required|integer|min:1',
            'available_ports' => 'required|integer|min:0',
            'charger_type' => 'required|string|max:100',
            'power_kw' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:500',
        ]);

        ChargingStation::create($validated);
        return redirect()->route('web.stations.index')->with('success', 'Station created successfully.');
    }

    public function edit(ChargingStation $station)
    {
        return view('stations.edit', compact('station'));
    }

    public function update(Request $request, ChargingStation $station)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'status' => 'required|in:active,inactive,maintenance',
            'total_ports' => 'required|integer|min:1',
            'available_ports' => 'required|integer|min:0',
            'charger_type' => 'required|string|max:100',
            'power_kw' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:500',
        ]);

        $station->update($validated);
        return redirect()->route('web.stations.show', $station)->with('success', 'Station updated successfully.');
    }

    public function destroy(ChargingStation $station)
    {
        $station->delete();
        return redirect()->route('web.stations.index')->with('success', 'Station deleted successfully.');
    }
}
