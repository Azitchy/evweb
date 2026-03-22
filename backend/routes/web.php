<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\IotController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\PricingController;
use App\Http\Controllers\Web\SessionController;
use App\Http\Controllers\Web\StationController;
use App\Http\Controllers\Web\SubscriptionController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('web.login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('web.logout');
    Route::get('/', [DashboardController::class, 'index'])->name('web.dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('web.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('web.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('web.users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('web.users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('web.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('web.users.update');
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('web.users.toggle-status');

    // Charging Stations
    Route::get('/stations', [StationController::class, 'index'])->name('web.stations.index');
    Route::get('/stations/create', [StationController::class, 'create'])->name('web.stations.create');
    Route::post('/stations', [StationController::class, 'store'])->name('web.stations.store');
    Route::get('/stations/{station}', [StationController::class, 'show'])->name('web.stations.show');
    Route::get('/stations/{station}/edit', [StationController::class, 'edit'])->name('web.stations.edit');
    Route::put('/stations/{station}', [StationController::class, 'update'])->name('web.stations.update');
    Route::delete('/stations/{station}', [StationController::class, 'destroy'])->name('web.stations.destroy');

    // Charging Sessions
    Route::get('/sessions', [SessionController::class, 'index'])->name('web.sessions.index');
    Route::get('/sessions/{session}', [SessionController::class, 'show'])->name('web.sessions.show');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('web.transactions.index');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('web.payments.index');

    // Pricing
    Route::get('/pricing', [PricingController::class, 'index'])->name('web.pricing.index');
    Route::post('/pricing', [PricingController::class, 'store'])->name('web.pricing.store');

    // Subscription Plans
    Route::get('/plans', [SubscriptionController::class, 'plans'])->name('web.plans.index');
    Route::get('/plans/create', [SubscriptionController::class, 'createPlan'])->name('web.plans.create');
    Route::post('/plans', [SubscriptionController::class, 'storePlan'])->name('web.plans.store');
    Route::get('/plans/{plan}/edit', [SubscriptionController::class, 'editPlan'])->name('web.plans.edit');
    Route::put('/plans/{plan}', [SubscriptionController::class, 'updatePlan'])->name('web.plans.update');

    // User Subscriptions
    Route::get('/subscriptions', [SubscriptionController::class, 'subscriptions'])->name('web.subscriptions.index');

    // IoT Devices
    Route::get('/iot', [IotController::class, 'index'])->name('web.iot.index');
    Route::get('/iot/create', [IotController::class, 'create'])->name('web.iot.create');
    Route::post('/iot', [IotController::class, 'store'])->name('web.iot.store');
    Route::get('/iot/{device}', [IotController::class, 'show'])->name('web.iot.show');
    Route::get('/iot/{device}/edit', [IotController::class, 'edit'])->name('web.iot.edit');
    Route::put('/iot/{device}', [IotController::class, 'update'])->name('web.iot.update');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('web.notifications.index');
});
