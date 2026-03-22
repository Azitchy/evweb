<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // IoT device registry for charging hardware
        Schema::create('iot_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('charging_station_id')->constrained()->onDelete('cascade');
            $table->string('device_id')->unique(); // unique hardware identifier
            $table->string('device_name');
            $table->enum('status', ['online', 'offline', 'error'])->default('offline');
            $table->string('firmware_version')->nullable();
            $table->decimal('current_power_kw', 6, 2)->default(0);
            $table->decimal('voltage', 6, 2)->default(0);
            $table->decimal('current_amps', 6, 2)->default(0);
            $table->decimal('temperature', 5, 2)->default(0);
            $table->timestamp('last_heartbeat_at')->nullable();
            $table->timestamps();
        });

        // IoT telemetry log
        Schema::create('iot_telemetry', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iot_device_id')->constrained()->onDelete('cascade');
            $table->foreignId('charging_session_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('power_kw', 6, 2);
            $table->decimal('voltage', 6, 2);
            $table->decimal('current_amps', 6, 2);
            $table->decimal('temperature', 5, 2);
            $table->decimal('energy_kwh', 8, 3)->default(0);
            $table->decimal('battery_percentage', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iot_telemetry');
        Schema::dropIfExists('iot_devices');
    }
};
