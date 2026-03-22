<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChargingController;
use App\Http\Controllers\Api\IotController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

// ── Public Routes ──
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ── Authenticated User Routes ──
Route::middleware(['auth:sanctum', 'active'])->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Profile
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::get('/profile/charging-history', [UserController::class, 'chargingHistory']);
    Route::get('/profile/transactions', [UserController::class, 'transactionHistory']);

    // Wallet
    Route::get('/wallet/balance', [WalletController::class, 'balance']);
    Route::post('/wallet/add-money', [WalletController::class, 'addMoney']);
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);

    // Charging
    Route::post('/charging/start', [ChargingController::class, 'start']);
    Route::post('/charging/{session}/stop', [ChargingController::class, 'stop']);
    Route::get('/charging/active', [ChargingController::class, 'activeSession']);
    Route::get('/charging/history', [ChargingController::class, 'history']);

    // Stations
    Route::get('/stations', [StationController::class, 'index']);
    Route::get('/stations/nearby', [StationController::class, 'nearby']);
    Route::get('/stations/{station}', [StationController::class, 'show']);

    // Payments
    Route::post('/payments/initiate', [PaymentController::class, 'initiate']);
    Route::post('/payments/verify/esewa', [PaymentController::class, 'verifyEsewa']);
    Route::post('/payments/verify/khalti', [PaymentController::class, 'verifyKhalti']);
    Route::get('/payments/history', [PaymentController::class, 'history']);

    // Subscriptions
    Route::get('/subscriptions/plans', [SubscriptionController::class, 'plans']);
    Route::post('/subscriptions/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::get('/subscriptions/current', [SubscriptionController::class, 'current']);
    Route::post('/subscriptions/cancel', [SubscriptionController::class, 'cancel']);
    Route::get('/subscriptions/history', [SubscriptionController::class, 'history']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/token', [NotificationController::class, 'registerToken']);
    Route::delete('/notifications/token', [NotificationController::class, 'removeToken']);

    // ── Admin Routes ──
    Route::middleware('admin')->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard']);

        // User Management
        Route::get('/users', [AdminController::class, 'users']);
        Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus']);

        // Pricing
        Route::get('/pricing', [AdminController::class, 'getCurrentPricing']);
        Route::post('/pricing', [AdminController::class, 'updatePricing']);

        // Transactions
        Route::get('/transactions', [AdminController::class, 'transactions']);

        // Charging Monitoring
        Route::get('/charging/active', [AdminController::class, 'activeSessions']);
        Route::get('/charging/logs', [AdminController::class, 'chargingLogs']);

        // Reports
        Route::get('/reports/revenue', [AdminController::class, 'revenueReport']);
        Route::get('/reports/user-activity', [AdminController::class, 'userActivityReport']);

        // IoT Device Management
        Route::post('/iot/register', [IotController::class, 'register']);
        Route::post('/iot/{deviceId}/heartbeat', [IotController::class, 'heartbeat']);
        Route::post('/iot/{deviceId}/telemetry', [IotController::class, 'telemetry']);
        Route::get('/iot/{deviceId}/status', [IotController::class, 'status']);
        Route::get('/iot/station/{stationId}', [IotController::class, 'stationDevices']);
        Route::get('/iot/{deviceId}/telemetry', [IotController::class, 'deviceTelemetry']);
        Route::patch('/iot/{deviceId}/status', [IotController::class, 'updateStatus']);
    });
});
