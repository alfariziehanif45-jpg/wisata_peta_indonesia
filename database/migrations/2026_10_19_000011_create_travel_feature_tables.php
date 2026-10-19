<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('favorite_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('place_key', 64);
            $table->string('name');
            $table->string('address')->nullable();
            $table->text('url')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'place_key']);
        });

        Schema::create('travel_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('notes')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('travel_plan_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_plan_id')->constrained('travel_plans')->cascadeOnDelete();
            $table->string('place_key', 64);
            $table->string('name');
            $table->string('address')->nullable();
            $table->text('url')->nullable();
            $table->boolean('visited')->default(false);
            $table->timestamp('visited_at')->nullable();
            $table->timestamps();
            $table->unique(['travel_plan_id', 'place_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_plan_places');
        Schema::dropIfExists('travel_plans');
        Schema::dropIfExists('favorite_places');
    }
};
