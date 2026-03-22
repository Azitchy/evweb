<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charging_stations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->enum('status', ['online', 'offline', 'maintenance'])->default('online');
            $table->integer('total_ports')->default(1);
            $table->integer('available_ports')->default(1);
            $table->string('charger_type')->default('Type 2'); // Type 1, Type 2, CCS, CHAdeMO
            $table->decimal('power_kw', 6, 2)->default(22.00); // kW rating
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });

        // Link charging sessions to stations
        Schema::table('charging_sessions', function (Blueprint $table) {
            $table->foreignId('charging_station_id')->nullable()->after('user_id')
                ->constrained('charging_stations')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('charging_sessions', function (Blueprint $table) {
            $table->dropForeign(['charging_station_id']);
            $table->dropColumn('charging_station_id');
        });
        Schema::dropIfExists('charging_stations');
    }
};
