<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function send(User $user, string $title, string $body, string $type = 'general', ?array $data = null): Notification
    {
        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'data' => $data,
        ]);

        // Send push notification via FCM
        $this->sendPushNotification($user, $title, $body, $data);

        return $notification;
    }

    public function sendToAllUsers(string $title, string $body, string $type = 'general', ?array $data = null): int
    {
        $users = User::where('role', 'user')->where('is_active', true)->get();
        $count = 0;

        foreach ($users as $user) {
            $this->send($user, $title, $body, $type, $data);
            $count++;
        }

        return $count;
    }

    public function registerToken(User $user, string $token, string $platform = 'android'): DeviceToken
    {
        return DeviceToken::updateOrCreate(
            ['user_id' => $user->id, 'token' => $token],
            ['platform' => $platform]
        );
    }

    public function removeToken(User $user, string $token): void
    {
        DeviceToken::where('user_id', $user->id)->where('token', $token)->delete();
    }

    public function getUserNotifications(User $user, int $perPage = 20)
    {
        return Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function markAsRead(User $user, int $notificationId): void
    {
        Notification::where('id', $notificationId)
            ->where('user_id', $user->id)
            ->update(['is_read' => true]);
    }

    public function markAllAsRead(User $user): void
    {
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function getUnreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }

    protected function sendPushNotification(User $user, string $title, string $body, ?array $data = null): void
    {
        $fcmServerKey = config('services.fcm.server_key');
        if (! $fcmServerKey) {
            return;
        }

        $tokens = DeviceToken::where('user_id', $user->id)->pluck('token')->toArray();
        if (empty($tokens)) {
            return;
        }

        try {
            Http::withHeaders([
                'Authorization' => 'key=' . $fcmServerKey,
                'Content-Type' => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', [
                'registration_ids' => $tokens,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'sound' => 'default',
                ],
                'data' => $data ?? [],
            ]);
        } catch (\Exception $e) {
            Log::warning('FCM push notification failed: ' . $e->getMessage());
        }
    }
}
