<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ChargingSession;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $query = ChargingSession::with(['user', 'chargingStation']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sessions = $query->latest()->paginate(20)->withQueryString();
        return view('sessions.index', compact('sessions'));
    }

    public function show(ChargingSession $session)
    {
        $session->load(['user', 'chargingStation']);
        return view('sessions.show', compact('session'));
    }
}
