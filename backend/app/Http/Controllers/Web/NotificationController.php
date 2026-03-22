<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::with('user');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->latest()->paginate(20)->withQueryString();
        return view('notifications.index', compact('notifications'));
    }
}
