<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->foreignId('origin_location_id')->constrained('locations')->cascadeOnDelete();
            $table->foreignId('destination_location_id')->constrained('locations')->cascadeOnDelete();
            $table->string('transport_mode')->default('road');
            $table->string('carrier')->nullable();
            $table->string('vehicle_reference')->nullable();
            $table->string('status')->default('dispatched');
            $table->decimal('distance_km', 10, 2)->default(0);
            $table->timestamp('departed_at')->nullable();
            $table->timestamp('expected_arrival_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
