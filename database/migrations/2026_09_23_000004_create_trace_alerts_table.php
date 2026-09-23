<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trace_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trace_event_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lot_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('severity')->default('medium');
            $table->text('message');
            $table->string('status')->default('new');
            $table->timestamp('detected_at')->useCurrent();
            $table->timestamp('resolved_at')->nullable();
            $table->unsignedBigInteger('resolved_by')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['lot_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trace_alerts');
    }
};
