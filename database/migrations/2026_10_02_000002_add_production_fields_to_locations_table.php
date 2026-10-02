<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('production_type')->nullable()->after('type');
            $table->string('production_method')->nullable()->after('production_type');
            $table->string('production_reference')->nullable()->after('production_method');
            $table->timestamp('production_started_at')->nullable()->after('production_reference');
            $table->timestamp('production_ended_at')->nullable()->after('production_started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn([
                'production_type',
                'production_method',
                'production_reference',
                'production_started_at',
                'production_ended_at',
            ]);
        });
    }
};
