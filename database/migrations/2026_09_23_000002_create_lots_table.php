<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('lot_number')->unique();
            $table->string('status')->default('active');
            $table->decimal('quantity', 12, 3)->default(0);
            $table->string('unit')->default('kg');
            $table->string('origin')->nullable();
            $table->string('location')->nullable();
            $table->timestamp('produced_at')->nullable();
            $table->string('public_token')->unique()->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['status', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lots');
    }
};
