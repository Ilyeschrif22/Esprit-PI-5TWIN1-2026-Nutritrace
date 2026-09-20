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
        Schema::table('users', function (Blueprint $table) {
            $table->string('fullname')->after('name');
            $table->string('cin', 8)->nullable()->unique()->after('password');
            $table->string('phone', 20)->nullable()->after('cin');
            $table->date('birthdate')->nullable()->after('phone');
            $table->string('governorate')->nullable()->after('birthdate');
            $table->string('city')->nullable()->after('governorate');
            $table->string('address')->nullable()->after('city');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'fullname',
                'cin',
                'phone',
                'birthdate',
                'governorate',
                'city',
                'address',
            ]);
        });
    }
};