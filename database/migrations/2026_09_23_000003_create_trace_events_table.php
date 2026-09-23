<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trace_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actor_id')->constrained('users')->cascadeOnDelete();
            $table->string('stage');
            $table->string('event_type');
            $table->timestamp('occurred_at');
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('destination_type')->nullable();
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->decimal('quantity', 12, 3)->nullable();
            $table->string('unit')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('metadata')->nullable();
            $table->string('status')->default('validated');
            $table->timestamps();

            $table->index(['lot_id', 'occurred_at']);
            $table->index(['stage', 'status']);
            $table->index('actor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trace_events');
    }
};
