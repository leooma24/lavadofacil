<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('vehicle_id')->nullable()->constrained();
            $table->foreignId('location_id')->nullable()->constrained();
            $table->foreignId('served_by_user_id')->constrained('users');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'vip', 'package'])->default('cash');
            $table->integer('earned_stamps')->default(0);
            $table->integer('points_earned')->default(0);
            $table->tinyInteger('satisfaction_rating')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('visited_at');
            $table->timestamps();

            $table->index('visited_at');
            $table->index(['customer_id', 'visited_at']);
        });

        Schema::create('visit_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_services');
        Schema::dropIfExists('visits');
    }
};
