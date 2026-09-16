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
            $table->string('prenom')->nullable()->after('name');
            $table->string('cin', 8)->nullable()->unique()->after('password');
            $table->string('telephone', 20)->nullable()->after('cin');
            $table->date('date_naissance')->nullable()->after('telephone');
            $table->string('genre', 20)->nullable()->after('date_naissance');
            $table->string('gouvernorat')->nullable()->after('genre');
            $table->string('delegation')->nullable()->after('gouvernorat');
            $table->string('ville')->nullable()->after('delegation');
            $table->string('adresse')->nullable()->after('ville');
            $table->string('code_postal', 10)->nullable()->after('adresse');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'prenom',
                'cin',
                'telephone',
                'date_naissance',
                'genre',
                'gouvernorat',
                'delegation',
                'ville',
                'adresse',
                'code_postal',
            ]);
        });
    }
};
