<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Basic, Premium, Enterprise
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('duration_days');
            $table->decimal('discount_percentage', 5, 2)->default(0); // discount on charging
            $table->decimal('free_charging_percentage', 8, 2)->default(0); // free % included
            $table->boolean('priority_support')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_plan_id')->constrained()->onDelete('cascade');
            $table->timestamp('starts_at')->useCurrent();
            $table->timestamp('expires_at')->useCurrent();
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->decimal('amount_paid', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
