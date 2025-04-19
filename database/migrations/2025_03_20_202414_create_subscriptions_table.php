<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('plan_id');
            $table->string('plan_price_id');
            $table->string('expires_at')->nullable();
            $table->string('starts_at')->nullable();
            $table->string('grace_ends_at')->nullable();
            $table->string('trial_ends_at')->nullable();
            $table->string('provider');
            $table->string('reference')->nullable();
            $table->boolean('auto_renews');
            $table->json('meta')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
