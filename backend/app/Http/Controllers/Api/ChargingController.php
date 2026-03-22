<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChargingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChargingController extends Controller
{
    public function __construct(
        protected ChargingService $chargingService
    ) {}

    public function start(Request $request): JsonResponse
    {
        $request->validate([
            'start_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'station_id' => ['nullable', 'exists:charging_stations,id'],
        ]);

        try {
            $session = $this->chargingService->startCharging(
                $request->user(),
                (float) $request->start_percentage,
                $request->station_id ? (int) $request->station_id : null
            );

            return response()->json([
                'message' => 'Charging started.',
                'session' => $session,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function stop(Request $request, int $sessionId): JsonResponse
    {
        $request->validate([
            'end_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        try {
            $session = $this->chargingService->stopCharging(
                $request->user(),
                $sessionId,
                (float) $request->end_percentage
            );

            return response()->json([
                'message' => 'Charging completed. Amount deducted from wallet.',
                'session' => $session,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function activeSession(Request $request): JsonResponse
    {
        $session = $this->chargingService->getActiveSession($request->user());

        return response()->json([
            'session' => $session,
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        $history = $this->chargingService->getUserHistory($request->user());

        return response()->json($history);
    }
}
