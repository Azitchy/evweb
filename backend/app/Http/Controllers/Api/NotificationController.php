<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function index(Request $request)
    {
        return response()->json(
            $this->notificationService->getUserNotifications($request->user())
        );
    }

    public function markAsRead(Request $request, int $id)
    {
        $this->notificationService->markAsRead($request->user(), $id);

        return response()->json(['message' => 'Marked as read']);
    }

    public function markAllAsRead(Request $request)
    {
        $this->notificationService->markAllAsRead($request->user());

        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function unreadCount(Request $request)
    {
        return response()->json([
            'count' => $this->notificationService->getUnreadCount($request->user()),
        ]);
    }

    public function registerToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'platform' => 'required|in:android,ios',
        ]);

        $this->notificationService->registerToken(
            $request->user(),
            $request->token,
            $request->platform
        );

        return response()->json(['message' => 'Token registered']);
    }

    public function removeToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $this->notificationService->removeToken($request->user(), $request->token);

        return response()->json(['message' => 'Token removed']);
    }
}
