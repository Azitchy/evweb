<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChargingStation;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index(Request $request)
    {
        $query = ChargingStation::where('status', '!=', 'removed');

        if ($request->filled('charger_type')) {
            $query->where('charger_type', $request->charger_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderBy('name')->paginate(20));
    }

    public function show(ChargingStation $station)
    {
        $station->load('iotDevices');
        return response()->json($station);
    }

    public function nearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'numeric|min:0.1|max:100', // km
        ]);

        $lat = $request->latitude;
        $lng = $request->longitude;
        $radius = $request->input('radius', 10); // default 10km

        $stations = ChargingStation::where('status', 'online')
            ->selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->limit(50)
            ->get();

        return response()->json($stations);
    }
}
