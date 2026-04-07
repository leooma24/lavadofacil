<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_stats', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('visits_count')->default(0);
            $table->decimal('revenue', 12, 2)->default(0);
            $table->integer('new_customers')->default(0);
            $table->integer('active_customers')->default(0);
            $table->integer('dormant_customers')->default(0);
            $table->integer('stamps_given')->default(0);
            $table->integer('prizes_claimed')->default(0);
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_stats');
    }
};
