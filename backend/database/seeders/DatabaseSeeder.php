<?php

namespace Database\Seeders;

use App\Models\ChargingSession;
use App\Models\ChargingStation;
use App\Models\DeviceToken;
use App\Models\IotDevice;
use App\Models\IotTelemetry;
use App\Models\Notification;
use App\Models\PaymentMethod;
use App\Models\PaymentTransaction;
use App\Models\PricingSetting;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ── Users (7) ──
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@evcharging.com', 'password' => bcrypt('password'), 'role' => 'admin', 'is_active' => true]);
        $user1 = User::create(['name' => 'Aarav Sharma', 'email' => 'aarav@evcharging.com', 'phone' => '9841000001', 'password' => bcrypt('password'), 'role' => 'user', 'is_active' => true]);
        $user2 = User::create(['name' => 'Sita Thapa', 'email' => 'sita@evcharging.com', 'phone' => '9841000002', 'password' => bcrypt('password'), 'role' => 'user', 'is_active' => true]);
        $user3 = User::create(['name' => 'Binod Gurung', 'email' => 'binod@evcharging.com', 'phone' => '9841000003', 'password' => bcrypt('password'), 'role' => 'user', 'is_active' => true]);
        $user4 = User::create(['name' => 'Priya Maharjan', 'email' => 'priya@evcharging.com', 'phone' => '9841000004', 'password' => bcrypt('password'), 'role' => 'user', 'is_active' => true]);
        $user5 = User::create(['name' => 'Rajesh Shrestha', 'email' => 'rajesh@evcharging.com', 'phone' => '9841000005', 'password' => bcrypt('password'), 'role' => 'user', 'is_active' => false]);
        $users = [$user1, $user2, $user3, $user4, $user5];

        // ── Wallets (7 – one per user) ──
        Wallet::create(['user_id' => $admin->id, 'balance' => 0]);
        $wallet1 = Wallet::create(['user_id' => $user1->id, 'balance' => 1500.00]);
        $wallet2 = Wallet::create(['user_id' => $user2->id, 'balance' => 2300.50]);
        $wallet3 = Wallet::create(['user_id' => $user3->id, 'balance' => 800.00]);
        $wallet4 = Wallet::create(['user_id' => $user4->id, 'balance' => 5000.00]);
        $wallet5 = Wallet::create(['user_id' => $user5->id, 'balance' => 120.75]);
        $wallets = [$wallet1, $wallet2, $wallet3, $wallet4, $wallet5];

        // ── Pricing Settings (5) ──
        PricingSetting::create(['price_per_percentage' => 5.00, 'is_active' => false]);
        PricingSetting::create(['price_per_percentage' => 6.00, 'is_active' => false]);
        PricingSetting::create(['price_per_percentage' => 6.50, 'is_active' => false]);
        PricingSetting::create(['price_per_percentage' => 7.00, 'is_active' => false]);
        PricingSetting::create(['price_per_percentage' => 7.50, 'is_active' => true]);

        // ── Charging Stations (7) ──
        $station1 = ChargingStation::create(['name' => 'Kathmandu Central Station', 'address' => 'New Baneshwor, Kathmandu', 'latitude' => 27.6915, 'longitude' => 85.3420, 'status' => 'active', 'total_ports' => 4, 'available_ports' => 3, 'charger_type' => 'DC Fast', 'power_kw' => 50.00, 'description' => 'Main hub in the heart of Kathmandu with DC fast chargers.']);
        $station2 = ChargingStation::create(['name' => 'Lalitpur EV Hub', 'address' => 'Pulchowk, Lalitpur', 'latitude' => 27.6780, 'longitude' => 85.3165, 'status' => 'active', 'total_ports' => 3, 'available_ports' => 2, 'charger_type' => 'AC Level 2', 'power_kw' => 22.00, 'description' => 'Convenient Level 2 charging near Pulchowk Campus.']);
        $station3 = ChargingStation::create(['name' => 'Bhaktapur Charge Point', 'address' => 'Suryabinayak, Bhaktapur', 'latitude' => 27.6710, 'longitude' => 85.4298, 'status' => 'active', 'total_ports' => 2, 'available_ports' => 2, 'charger_type' => 'DC Fast', 'power_kw' => 60.00, 'description' => 'High-power DC station on the highway corridor.']);
        $station4 = ChargingStation::create(['name' => 'Pokhara Lakeside Station', 'address' => 'Lakeside, Pokhara', 'latitude' => 28.2096, 'longitude' => 83.9856, 'status' => 'active', 'total_ports' => 3, 'available_ports' => 3, 'charger_type' => 'AC Level 2', 'power_kw' => 22.00, 'description' => 'Scenic lakeside charging for tourists and locals.']);
        $station5 = ChargingStation::create(['name' => 'Chitwan EV Station', 'address' => 'Bharatpur, Chitwan', 'latitude' => 27.6833, 'longitude' => 84.4333, 'status' => 'active', 'total_ports' => 2, 'available_ports' => 1, 'charger_type' => 'DC Fast', 'power_kw' => 50.00, 'description' => 'Terai highway pit-stop for long-range EV trips.']);
        $station6 = ChargingStation::create(['name' => 'Biratnagar Super Charger', 'address' => 'Rani, Biratnagar', 'latitude' => 26.4525, 'longitude' => 87.2718, 'status' => 'maintenance', 'total_ports' => 4, 'available_ports' => 0, 'charger_type' => 'DC Fast', 'power_kw' => 120.00, 'description' => 'Under maintenance – ultra-fast charger upgrade in progress.']);
        $station7 = ChargingStation::create(['name' => 'Butwal Green Charge', 'address' => 'Traffic Chowk, Butwal', 'latitude' => 27.7006, 'longitude' => 83.4486, 'status' => 'inactive', 'total_ports' => 2, 'available_ports' => 0, 'charger_type' => 'AC Level 2', 'power_kw' => 22.00, 'description' => 'Currently inactive, awaiting grid connection.']);
        $stations = [$station1, $station2, $station3, $station4, $station5, $station6, $station7];

        // ── Charging Sessions (8) ──
        $pricePerPct = 7.50;
        $session1 = ChargingSession::create(['user_id' => $user1->id, 'charging_station_id' => $station1->id, 'start_percentage' => 20.00, 'end_percentage' => 85.00, 'charged_percentage' => 65.00, 'cost' => 65 * $pricePerPct, 'price_per_percentage' => $pricePerPct, 'status' => 'completed', 'started_at' => Carbon::now()->subDays(5)->setHour(9), 'ended_at' => Carbon::now()->subDays(5)->setHour(10)]);
        $session2 = ChargingSession::create(['user_id' => $user2->id, 'charging_station_id' => $station2->id, 'start_percentage' => 10.00, 'end_percentage' => 90.00, 'charged_percentage' => 80.00, 'cost' => 80 * $pricePerPct, 'price_per_percentage' => $pricePerPct, 'status' => 'completed', 'started_at' => Carbon::now()->subDays(4)->setHour(14), 'ended_at' => Carbon::now()->subDays(4)->setHour(15)]);
        $session3 = ChargingSession::create(['user_id' => $user3->id, 'charging_station_id' => $station3->id, 'start_percentage' => 30.00, 'end_percentage' => 70.00, 'charged_percentage' => 40.00, 'cost' => 40 * $pricePerPct, 'price_per_percentage' => $pricePerPct, 'status' => 'completed', 'started_at' => Carbon::now()->subDays(3)->setHour(8), 'ended_at' => Carbon::now()->subDays(3)->setHour(9)]);
        $session4 = ChargingSession::create(['user_id' => $user4->id, 'charging_station_id' => $station4->id, 'start_percentage' => 5.00, 'end_percentage' => 95.00, 'charged_percentage' => 90.00, 'cost' => 90 * $pricePerPct, 'price_per_percentage' => $pricePerPct, 'status' => 'completed', 'started_at' => Carbon::now()->subDays(2)->setHour(11), 'ended_at' => Carbon::now()->subDays(2)->setHour(13)]);
        $session5 = ChargingSession::create(['user_id' => $user5->id, 'charging_station_id' => $station5->id, 'start_percentage' => 40.00, 'end_percentage' => 80.00, 'charged_percentage' => 40.00, 'cost' => 40 * $pricePerPct, 'price_per_percentage' => $pricePerPct, 'status' => 'completed', 'started_at' => Carbon::now()->subDays(1)->setHour(16), 'ended_at' => Carbon::now()->subDays(1)->setHour(17)]);
        $session6 = ChargingSession::create(['user_id' => $user1->id, 'charging_station_id' => $station1->id, 'start_percentage' => 15.00, 'end_percentage' => null, 'charged_percentage' => null, 'cost' => null, 'price_per_percentage' => $pricePerPct, 'status' => 'charging', 'started_at' => Carbon::now()->subMinutes(30), 'ended_at' => null]);
        $session7 = ChargingSession::create(['user_id' => $user2->id, 'charging_station_id' => $station3->id, 'start_percentage' => 50.00, 'end_percentage' => 55.00, 'charged_percentage' => 5.00, 'cost' => 5 * $pricePerPct, 'price_per_percentage' => $pricePerPct, 'status' => 'cancelled', 'started_at' => Carbon::now()->subDays(6)->setHour(10), 'ended_at' => Carbon::now()->subDays(6)->setHour(10)->addMinutes(15)]);
        $session8 = ChargingSession::create(['user_id' => $user3->id, 'charging_station_id' => $station2->id, 'start_percentage' => 25.00, 'end_percentage' => null, 'charged_percentage' => null, 'cost' => null, 'price_per_percentage' => $pricePerPct, 'status' => 'charging', 'started_at' => Carbon::now()->subMinutes(10), 'ended_at' => null]);
        $sessions = [$session1, $session2, $session3, $session4, $session5];

        // ── Transactions (10 – wallet top-ups + session debits) ──
        // Top-ups
        Transaction::create(['user_id' => $user1->id, 'wallet_id' => $wallet1->id, 'type' => 'credit', 'amount' => 2000.00, 'balance_after' => 2000.00, 'description' => 'Wallet top-up via eSewa']);
        Transaction::create(['user_id' => $user2->id, 'wallet_id' => $wallet2->id, 'type' => 'credit', 'amount' => 3000.00, 'balance_after' => 3000.00, 'description' => 'Wallet top-up via Khalti']);
        Transaction::create(['user_id' => $user3->id, 'wallet_id' => $wallet3->id, 'type' => 'credit', 'amount' => 1500.00, 'balance_after' => 1500.00, 'description' => 'Wallet top-up via eSewa']);
        Transaction::create(['user_id' => $user4->id, 'wallet_id' => $wallet4->id, 'type' => 'credit', 'amount' => 5000.00, 'balance_after' => 5000.00, 'description' => 'Wallet top-up via Khalti']);
        Transaction::create(['user_id' => $user5->id, 'wallet_id' => $wallet5->id, 'type' => 'credit', 'amount' => 500.00, 'balance_after' => 500.00, 'description' => 'Wallet top-up via eSewa']);
        // Session debits
        Transaction::create(['user_id' => $user1->id, 'wallet_id' => $wallet1->id, 'charging_session_id' => $session1->id, 'type' => 'debit', 'amount' => $session1->cost, 'balance_after' => 1500.00, 'description' => 'Charging session #' . $session1->id]);
        Transaction::create(['user_id' => $user2->id, 'wallet_id' => $wallet2->id, 'charging_session_id' => $session2->id, 'type' => 'debit', 'amount' => $session2->cost, 'balance_after' => 2300.50, 'description' => 'Charging session #' . $session2->id]);
        Transaction::create(['user_id' => $user3->id, 'wallet_id' => $wallet3->id, 'charging_session_id' => $session3->id, 'type' => 'debit', 'amount' => $session3->cost, 'balance_after' => 800.00, 'description' => 'Charging session #' . $session3->id]);
        Transaction::create(['user_id' => $user4->id, 'wallet_id' => $wallet4->id, 'charging_session_id' => $session4->id, 'type' => 'debit', 'amount' => $session4->cost, 'balance_after' => 5000.00 - $session4->cost, 'description' => 'Charging session #' . $session4->id]);
        Transaction::create(['user_id' => $user5->id, 'wallet_id' => $wallet5->id, 'charging_session_id' => $session5->id, 'type' => 'debit', 'amount' => $session5->cost, 'balance_after' => 120.75, 'description' => 'Charging session #' . $session5->id]);

        // ── Payment Methods (6) ──
        PaymentMethod::create(['user_id' => $user1->id, 'gateway' => 'esewa', 'gateway_customer_id' => 'esewa_cust_1001', 'is_default' => true]);
        PaymentMethod::create(['user_id' => $user1->id, 'gateway' => 'khalti', 'gateway_customer_id' => 'khalti_cust_1001', 'is_default' => false]);
        PaymentMethod::create(['user_id' => $user2->id, 'gateway' => 'khalti', 'gateway_customer_id' => 'khalti_cust_1002', 'is_default' => true]);
        PaymentMethod::create(['user_id' => $user3->id, 'gateway' => 'esewa', 'gateway_customer_id' => 'esewa_cust_1003', 'is_default' => true]);
        PaymentMethod::create(['user_id' => $user4->id, 'gateway' => 'khalti', 'gateway_customer_id' => 'khalti_cust_1004', 'is_default' => true]);
        PaymentMethod::create(['user_id' => $user5->id, 'gateway' => 'esewa', 'gateway_customer_id' => 'esewa_cust_1005', 'is_default' => true]);

        // ── Payment Transactions (6) ──
        PaymentTransaction::create(['user_id' => $user1->id, 'wallet_id' => $wallet1->id, 'gateway' => 'esewa', 'gateway_transaction_id' => 'TXN_ESW_20260317_001', 'amount' => 2000.00, 'status' => 'completed', 'purpose' => 'wallet_topup', 'gateway_response' => ['code' => 'SUCCESS', 'ref' => 'ESW001']]);
        PaymentTransaction::create(['user_id' => $user2->id, 'wallet_id' => $wallet2->id, 'gateway' => 'khalti', 'gateway_transaction_id' => 'TXN_KHL_20260317_002', 'amount' => 3000.00, 'status' => 'completed', 'purpose' => 'wallet_topup', 'gateway_response' => ['code' => 'SUCCESS', 'ref' => 'KHL002']]);
        PaymentTransaction::create(['user_id' => $user3->id, 'wallet_id' => $wallet3->id, 'gateway' => 'esewa', 'gateway_transaction_id' => 'TXN_ESW_20260318_003', 'amount' => 1500.00, 'status' => 'completed', 'purpose' => 'wallet_topup', 'gateway_response' => ['code' => 'SUCCESS', 'ref' => 'ESW003']]);
        PaymentTransaction::create(['user_id' => $user4->id, 'wallet_id' => $wallet4->id, 'gateway' => 'khalti', 'gateway_transaction_id' => 'TXN_KHL_20260319_004', 'amount' => 5000.00, 'status' => 'completed', 'purpose' => 'wallet_topup', 'gateway_response' => ['code' => 'SUCCESS', 'ref' => 'KHL004']]);
        PaymentTransaction::create(['user_id' => $user5->id, 'wallet_id' => $wallet5->id, 'gateway' => 'esewa', 'gateway_transaction_id' => 'TXN_ESW_20260320_005', 'amount' => 500.00, 'status' => 'completed', 'purpose' => 'wallet_topup', 'gateway_response' => ['code' => 'SUCCESS', 'ref' => 'ESW005']]);
        PaymentTransaction::create(['user_id' => $user1->id, 'wallet_id' => $wallet1->id, 'gateway' => 'khalti', 'gateway_transaction_id' => null, 'amount' => 1000.00, 'status' => 'failed', 'purpose' => 'wallet_topup', 'gateway_response' => ['code' => 'INSUFFICIENT_BALANCE']]);

        // ── Subscription Plans (5) ──
        $planBasic = SubscriptionPlan::create(['name' => 'Basic', 'description' => 'Get 5% discount on all charging sessions', 'price' => 299.00, 'duration_days' => 30, 'discount_percentage' => 5.00, 'free_charging_percentage' => 0.00, 'priority_support' => false, 'is_active' => true]);
        $planStandard = SubscriptionPlan::create(['name' => 'Standard', 'description' => 'Get 10% discount on all charging sessions', 'price' => 499.00, 'duration_days' => 30, 'discount_percentage' => 10.00, 'free_charging_percentage' => 0.00, 'priority_support' => false, 'is_active' => true]);
        $planPremium = SubscriptionPlan::create(['name' => 'Premium', 'description' => 'Get 15% discount and priority support', 'price' => 799.00, 'duration_days' => 30, 'discount_percentage' => 15.00, 'free_charging_percentage' => 0.00, 'priority_support' => true, 'is_active' => true]);
        $planEnterprise = SubscriptionPlan::create(['name' => 'Enterprise', 'description' => 'Get 25% discount, 10% free charging, and priority support', 'price' => 1499.00, 'duration_days' => 30, 'discount_percentage' => 25.00, 'free_charging_percentage' => 10.00, 'priority_support' => true, 'is_active' => true]);
        $planAnnual = SubscriptionPlan::create(['name' => 'Annual Pro', 'description' => 'Get 30% discount, 15% free charging, and priority support for a full year', 'price' => 9999.00, 'duration_days' => 365, 'discount_percentage' => 30.00, 'free_charging_percentage' => 15.00, 'priority_support' => true, 'is_active' => true]);

        // ── User Subscriptions (5) ──
        UserSubscription::create(['user_id' => $user1->id, 'subscription_plan_id' => $planPremium->id, 'starts_at' => Carbon::now()->subDays(10), 'expires_at' => Carbon::now()->addDays(20), 'status' => 'active', 'amount_paid' => 799.00]);
        UserSubscription::create(['user_id' => $user2->id, 'subscription_plan_id' => $planBasic->id, 'starts_at' => Carbon::now()->subDays(25), 'expires_at' => Carbon::now()->addDays(5), 'status' => 'active', 'amount_paid' => 299.00]);
        UserSubscription::create(['user_id' => $user3->id, 'subscription_plan_id' => $planStandard->id, 'starts_at' => Carbon::now()->subDays(40), 'expires_at' => Carbon::now()->subDays(10), 'status' => 'expired', 'amount_paid' => 499.00]);
        UserSubscription::create(['user_id' => $user4->id, 'subscription_plan_id' => $planEnterprise->id, 'starts_at' => Carbon::now()->subDays(5), 'expires_at' => Carbon::now()->addDays(25), 'status' => 'active', 'amount_paid' => 1499.00]);
        UserSubscription::create(['user_id' => $user5->id, 'subscription_plan_id' => $planBasic->id, 'starts_at' => Carbon::now()->subDays(35), 'expires_at' => Carbon::now()->subDays(5), 'status' => 'cancelled', 'amount_paid' => 299.00]);

        // ── Notifications (7) ──
        Notification::create(['user_id' => $user1->id, 'title' => 'Charging Complete', 'body' => 'Your EV has been charged to 85%. Total cost: Rs. 487.50.', 'type' => 'charging', 'data' => ['session_id' => $session1->id], 'is_read' => true]);
        Notification::create(['user_id' => $user2->id, 'title' => 'Wallet Top-up Successful', 'body' => 'Rs. 3,000 has been added to your wallet via Khalti.', 'type' => 'payment', 'data' => ['amount' => 3000], 'is_read' => true]);
        Notification::create(['user_id' => $user3->id, 'title' => 'Subscription Expired', 'body' => 'Your Standard plan has expired. Renew to keep enjoying discounts.', 'type' => 'subscription', 'data' => ['plan' => 'Standard'], 'is_read' => false]);
        Notification::create(['user_id' => $user4->id, 'title' => 'Welcome to Enterprise!', 'body' => 'You now enjoy 25% discount and priority support on all sessions.', 'type' => 'subscription', 'data' => ['plan' => 'Enterprise'], 'is_read' => true]);
        Notification::create(['user_id' => $user1->id, 'title' => 'Session In Progress', 'body' => 'Your charging session at Kathmandu Central Station has started.', 'type' => 'charging', 'data' => ['session_id' => $session6->id], 'is_read' => false]);
        Notification::create(['user_id' => $user5->id, 'title' => 'Account Deactivated', 'body' => 'Your account has been deactivated. Contact support for assistance.', 'type' => 'general', 'is_read' => false]);
        Notification::create(['user_id' => $user2->id, 'title' => 'New Station Nearby', 'body' => 'Bhaktapur Charge Point is now available near you with DC Fast chargers.', 'type' => 'general', 'is_read' => false]);

        // ── Device Tokens (5) ──
        DeviceToken::create(['user_id' => $user1->id, 'token' => 'fcm_token_aarav_device1_abc123def456', 'platform' => 'android']);
        DeviceToken::create(['user_id' => $user2->id, 'token' => 'fcm_token_sita_device1_ghi789jkl012', 'platform' => 'ios']);
        DeviceToken::create(['user_id' => $user3->id, 'token' => 'fcm_token_binod_device1_mno345pqr678', 'platform' => 'android']);
        DeviceToken::create(['user_id' => $user4->id, 'token' => 'fcm_token_priya_device1_stu901vwx234', 'platform' => 'ios']);
        DeviceToken::create(['user_id' => $user5->id, 'token' => 'fcm_token_rajesh_device1_yza567bcd890', 'platform' => 'android']);

        // ── IoT Devices (7 – at least one per active station) ──
        $iot1 = IotDevice::create(['charging_station_id' => $station1->id, 'device_id' => 'IOT-KTM-001', 'device_name' => 'KTM DC Charger A', 'status' => 'online', 'firmware_version' => 'v2.1.0', 'current_power_kw' => 48.50, 'voltage' => 400.00, 'current_amps' => 121.25, 'temperature' => 42.30, 'last_heartbeat_at' => Carbon::now()->subMinutes(2)]);
        $iot2 = IotDevice::create(['charging_station_id' => $station1->id, 'device_id' => 'IOT-KTM-002', 'device_name' => 'KTM DC Charger B', 'status' => 'online', 'firmware_version' => 'v2.1.0', 'current_power_kw' => 0.00, 'voltage' => 0.00, 'current_amps' => 0.00, 'temperature' => 28.10, 'last_heartbeat_at' => Carbon::now()->subMinutes(1)]);
        $iot3 = IotDevice::create(['charging_station_id' => $station2->id, 'device_id' => 'IOT-LAL-001', 'device_name' => 'Lalitpur AC Unit 1', 'status' => 'online', 'firmware_version' => 'v1.8.3', 'current_power_kw' => 21.50, 'voltage' => 230.00, 'current_amps' => 93.48, 'temperature' => 38.50, 'last_heartbeat_at' => Carbon::now()->subMinutes(3)]);
        $iot4 = IotDevice::create(['charging_station_id' => $station3->id, 'device_id' => 'IOT-BKT-001', 'device_name' => 'Bhaktapur DC Unit 1', 'status' => 'offline', 'firmware_version' => 'v2.0.1', 'current_power_kw' => 0.00, 'voltage' => 0.00, 'current_amps' => 0.00, 'temperature' => 25.00, 'last_heartbeat_at' => Carbon::now()->subHours(6)]);
        $iot5 = IotDevice::create(['charging_station_id' => $station4->id, 'device_id' => 'IOT-PKR-001', 'device_name' => 'Pokhara AC Unit 1', 'status' => 'online', 'firmware_version' => 'v1.8.3', 'current_power_kw' => 18.00, 'voltage' => 230.00, 'current_amps' => 78.26, 'temperature' => 35.20, 'last_heartbeat_at' => Carbon::now()->subMinutes(1)]);
        $iot6 = IotDevice::create(['charging_station_id' => $station5->id, 'device_id' => 'IOT-CTW-001', 'device_name' => 'Chitwan DC Unit 1', 'status' => 'online', 'firmware_version' => 'v2.1.0', 'current_power_kw' => 45.00, 'voltage' => 400.00, 'current_amps' => 112.50, 'temperature' => 44.00, 'last_heartbeat_at' => Carbon::now()->subMinutes(5)]);
        $iot7 = IotDevice::create(['charging_station_id' => $station6->id, 'device_id' => 'IOT-BRT-001', 'device_name' => 'Biratnagar Super Charger', 'status' => 'error', 'firmware_version' => 'v3.0.0-beta', 'current_power_kw' => 0.00, 'voltage' => 0.00, 'current_amps' => 0.00, 'temperature' => 65.00, 'last_heartbeat_at' => Carbon::now()->subHours(12)]);
        $iotDevices = [$iot1, $iot2, $iot3, $iot4, $iot5, $iot6, $iot7];

        // ── IoT Telemetry (10 – spread across devices/sessions) ──
        IotTelemetry::create(['iot_device_id' => $iot1->id, 'charging_session_id' => $session6->id, 'power_kw' => 48.50, 'voltage' => 400.00, 'current_amps' => 121.25, 'temperature' => 42.30, 'energy_kwh' => 12.125, 'battery_percentage' => 35.00]);
        IotTelemetry::create(['iot_device_id' => $iot1->id, 'charging_session_id' => $session6->id, 'power_kw' => 47.80, 'voltage' => 398.00, 'current_amps' => 120.10, 'temperature' => 43.10, 'energy_kwh' => 24.075, 'battery_percentage' => 50.00]);
        IotTelemetry::create(['iot_device_id' => $iot1->id, 'charging_session_id' => $session1->id, 'power_kw' => 50.00, 'voltage' => 402.00, 'current_amps' => 124.38, 'temperature' => 40.50, 'energy_kwh' => 35.000, 'battery_percentage' => 85.00]);
        IotTelemetry::create(['iot_device_id' => $iot3->id, 'charging_session_id' => $session8->id, 'power_kw' => 21.50, 'voltage' => 230.00, 'current_amps' => 93.48, 'temperature' => 38.50, 'energy_kwh' => 3.583, 'battery_percentage' => 32.00]);
        IotTelemetry::create(['iot_device_id' => $iot3->id, 'charging_session_id' => $session2->id, 'power_kw' => 22.00, 'voltage' => 231.00, 'current_amps' => 95.24, 'temperature' => 37.00, 'energy_kwh' => 22.000, 'battery_percentage' => 90.00]);
        IotTelemetry::create(['iot_device_id' => $iot5->id, 'charging_session_id' => $session4->id, 'power_kw' => 20.00, 'voltage' => 229.00, 'current_amps' => 87.34, 'temperature' => 36.00, 'energy_kwh' => 18.500, 'battery_percentage' => 70.00]);
        IotTelemetry::create(['iot_device_id' => $iot5->id, 'charging_session_id' => $session4->id, 'power_kw' => 18.00, 'voltage' => 228.00, 'current_amps' => 78.95, 'temperature' => 35.50, 'energy_kwh' => 30.000, 'battery_percentage' => 95.00]);
        IotTelemetry::create(['iot_device_id' => $iot6->id, 'charging_session_id' => $session5->id, 'power_kw' => 45.00, 'voltage' => 400.00, 'current_amps' => 112.50, 'temperature' => 44.00, 'energy_kwh' => 15.000, 'battery_percentage' => 60.00]);
        IotTelemetry::create(['iot_device_id' => $iot6->id, 'charging_session_id' => $session5->id, 'power_kw' => 42.00, 'voltage' => 398.00, 'current_amps' => 105.53, 'temperature' => 45.20, 'energy_kwh' => 28.000, 'battery_percentage' => 80.00]);
        IotTelemetry::create(['iot_device_id' => $iot4->id, 'charging_session_id' => $session3->id, 'power_kw' => 55.00, 'voltage' => 405.00, 'current_amps' => 135.80, 'temperature' => 41.00, 'energy_kwh' => 20.000, 'battery_percentage' => 70.00]);
    }
}
