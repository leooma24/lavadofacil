<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raffles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('prize_description');
            $table->string('prize_image')->nullable();
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->integer('tickets_required')->default(1);
            $table->integer('max_tickets_per_customer')->nullable();
            $table->date('draw_date');
            $table->enum('status', ['active', 'drawn', 'closed'])->default('active');
            $table->foreignId('winner_customer_id')->nullable()->constrained('customers');
            $table->string('winning_ticket_number')->nullable();
            $table->timestamps();

            $table->unique(['month', 'year']);
        });

        Schema::create('raffle_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raffle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('visit_id')->nullable()->constrained();
            $table->string('ticket_number');
            $table->timestamp('generated_at');
            $table->timestamps();

            $table->unique(['raffle_id', 'ticket_number']);
            $table->index(['raffle_id', 'customer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raffle_tickets');
        Schema::dropIfExists('raffles');
    }
};
