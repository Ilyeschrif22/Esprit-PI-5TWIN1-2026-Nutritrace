<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transformations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('input_lot_id')->constrained('lots')->cascadeOnDelete();
            $table->foreignId('output_lot_id')->nullable()->constrained('lots')->nullOnDelete();
            $table->string('process_name')->default('Transformation');
            $table->decimal('input_quantity', 12, 3)->nullable();
            $table->decimal('output_quantity', 12, 3)->nullable();
            $table->decimal('loss_quantity', 12, 3)->default(0);
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->timestamp('occurred_at')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transformations');
    }
};
