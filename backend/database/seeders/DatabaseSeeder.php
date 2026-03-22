<?php

namespace Database\Seeders;

use App\Models\ChargingStation;
use App\Models\PricingSetting;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@evcharging.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        Wallet::create(['user_id' => $admin->id, 'balance' => 0]);

        // Create test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@evcharging.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);
        Wallet::create(['user_id' => $user->id, 'balance' => 1000]);

        // Set default pricing: Rs. 7 per percentage
        PricingSetting::create([
            'price_per_percentage' => 7.00,
            'is_active' => true,
        ]);

        // Charging Stations
        $stations = [
            ['name' => 'Kathmandu Central Station', 'address' => 'New Baneshwor, Kathmandu', 'latitude' => 27.6915, 'longitude' => 85.3420, 'status' => 'online', 'total_ports' => 4, 'available_ports' => 4, 'charger_type' => 'DC Fast', 'power_kw' => 50.00],
            ['name' => 'Lalitpur EV Hub', 'address' => 'Pulchowk, Lalitpur', 'latitude' => 27.6780, 'longitude' => 85.3165, 'status' => 'online', 'total_ports' => 3, 'available_ports' => 3, 'charger_type' => 'AC Level 2', 'power_kw' => 22.00],
            ['name' => 'Bhaktapur Charge Point', 'address' => 'Suryabinayak, Bhaktapur', 'latitude' => 27.6710, 'longitude' => 85.4298, 'status' => 'online', 'total_ports' => 2, 'available_ports' => 2, 'charger_type' => 'DC Fast', 'power_kw' => 60.00],
            ['name' => 'Pokhara Lakeside Station', 'address' => 'Lakeside, Pokhara', 'latitude' => 28.2096, 'longitude' => 83.9856, 'status' => 'online', 'total_ports' => 3, 'available_ports' => 3, 'charger_type' => 'AC Level 2', 'power_kw' => 22.00],
            ['name' => 'Chitwan EV Station', 'address' => 'Bharatpur, Chitwan', 'latitude' => 27.6833, 'longitude' => 84.4333, 'status' => 'online', 'total_ports' => 2, 'available_ports' => 2, 'charger_type' => 'DC Fast', 'power_kw' => 50.00],
        ];

        foreach ($stations as $station) {
            ChargingStation::create($station);
        }

        // Subscription Plans
        SubscriptionPlan::create([
            'name' => 'Basic',
            'description' => 'Get 5% discount on all charging sessions',
            'price' => 299.00,
            'duration_days' => 30,
            'discount_percentage' => 5.00,
            'free_charging_percentage' => 0.00,
            'priority_support' => false,
            'is_active' => true,
        ]);

        SubscriptionPlan::create([
            'name' => 'Premium',
            'description' => 'Get 15% discount and priority support',
            'price' => 799.00,
            'duration_days' => 30,
            'discount_percentage' => 15.00,
            'free_charging_percentage' => 0.00,
            'priority_support' => true,
            'is_active' => true,
        ]);

        SubscriptionPlan::create([
            'name' => 'Enterprise',
            'description' => 'Get 25% discount, 10% free charging, and priority support',
            'price' => 1499.00,
            'duration_days' => 30,
            'discount_percentage' => 25.00,
            'free_charging_percentage' => 10.00,
            'priority_support' => true,
            'is_active' => true,
        ]);
    }
}
