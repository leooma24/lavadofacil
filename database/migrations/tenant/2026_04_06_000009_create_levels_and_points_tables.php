<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('level_configs', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ['bronze', 'silver', 'gold', 'platinum'])->unique();
            $table->integer('min_visits')->default(0);
            $table->decimal('min_spent', 10, 2)->default(0);
            $table->decimal('multiplier', 5, 2)->default(1.00);
            $table->json('perks')->nullable();
            $table->string('color')->default('#cd7f32');
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('visit_id')->nullable()->constrained();
            $table->enum('type', ['earned', 'redeemed', 'bonus', 'expired', 'refund']);
            $table->integer('amount');
            $table->integer('balance_after');
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
        Schema::dropIfExists('level_configs');
    }
};
