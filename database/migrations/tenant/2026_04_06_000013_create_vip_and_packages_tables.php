<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vip_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->string('plan_name');
            $table->decimal('monthly_price', 10, 2);
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->enum('status', ['active', 'cancelled', 'expired'])->default('active');
            $table->boolean('auto_renew')->default(true);
            $table->integer('washes_included')->default(0);
            $table->integer('washes_used')->default(0);
            $table->timestamps();

            $table->index(['customer_id', 'status']);
        });

        Schema::create('prepaid_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('washes_count');
            $table->decimal('price', 10, 2);
            $table->integer('validity_days')->default(365);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('customer_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('package_id')->constrained('prepaid_packages');
            $table->integer('washes_total');
            $table->integer('washes_remaining');
            $table->timestamp('purchased_at');
            $table->timestamp('expires_at');
            $table->decimal('amount_paid', 10, 2);
            $table->string('payment_method')->default('cash');
            $table->timestamps();

            $table->index(['customer_id', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_packages');
        Schema::dropIfExists('prepaid_packages');
        Schema::dropIfExists('vip_subscriptions');
    }
};
