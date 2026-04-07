<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_challenges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->enum('goal_type', ['visits', 'spent', 'services_count', 'specific_service', 'referrals']);
            $table->decimal('goal_value', 10, 2);
            $table->string('reward_description');
            $table->integer('reward_points')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('challenge_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained('monthly_challenges')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained();
            $table->decimal('current_value', 10, 2)->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();

            $table->unique(['challenge_id', 'customer_id']);
        });

        Schema::create('monthly_rankings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->foreignId('customer_id')->constrained();
            $table->integer('position');
            $table->integer('visits_count')->default(0);
            $table->decimal('total_spent', 10, 2)->default(0);
            $table->timestamp('created_at')->nullable();

            $table->unique(['month', 'year', 'customer_id']);
            $table->index(['month', 'year', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_rankings');
        Schema::dropIfExists('challenge_progress');
        Schema::dropIfExists('monthly_challenges');
    }
};
