<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('stamps_count')->default(0);
            $table->integer('completed_count')->default(0);
            $table->integer('current_card_number')->default(1);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('last_stamp_at')->nullable();
            $table->boolean('is_complete')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('stamps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loyalty_card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visit_id')->nullable()->constrained();
            $table->foreignId('stamped_by_user_id')->constrained('users');
            $table->timestamp('stamped_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stamps');
        Schema::dropIfExists('loyalty_cards');
    }
};
