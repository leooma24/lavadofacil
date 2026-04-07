<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained();
            $table->enum('type', ['in_shop', 'at_home'])->default('in_shop');
            $table->string('address')->nullable();
            $table->dateTime('scheduled_at');
            $table->integer('queue_position')->nullable();
            $table->enum('status', [
                'pending',     // recién creada por cliente
                'confirmed',   // dueño la aceptó
                'in_queue',    // ya en cola, esperando turno
                'in_progress', // se está atendiendo
                'ready',       // ya puede traerlo (notificado)
                'completed',   // terminó
                'cancelled',
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('ready_notified_at')->nullable();
            $table->enum('customer_response', ['going', 'cant_make_it'])->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
