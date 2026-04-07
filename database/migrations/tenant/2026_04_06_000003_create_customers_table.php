<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('level', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze');
            $table->integer('total_visits')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0);
            $table->integer('points_balance')->default(0);
            $table->integer('current_streak')->default(0);
            $table->integer('longest_streak')->default(0);
            $table->date('last_streak_date')->nullable();
            $table->timestamp('last_visit_at')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->foreignId('referred_by_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->boolean('whatsapp_opt_in')->default(true);
            $table->boolean('is_vip')->default(false);
            $table->timestamp('vip_until')->nullable();
            $table->text('notes')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();

            $table->index('level');
            $table->index('last_visit_at');
            $table->index('is_vip');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
