<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charging_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('start_percentage', 5, 2);
            $table->decimal('end_percentage', 5, 2)->nullable();
            $table->decimal('charged_percentage', 5, 2)->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->decimal('price_per_percentage', 10, 2);
            $table->enum('status', ['charging', 'completed', 'cancelled'])->default('charging');
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charging_sessions');
    }
};
