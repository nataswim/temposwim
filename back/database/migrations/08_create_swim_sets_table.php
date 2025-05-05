<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create the "swim_sets" table.
 * 🇫🇷 Migration pour créer la table "swim_sets".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations.
     * 🇫🇷 Exécuter la migration.
     */
    public function up(): void
    {
        Schema::create('swim_sets', function (Blueprint $table) {
            // 🇬🇧 Primary key (auto-increment).
            // 🇫🇷 Clé primaire (auto-incrémentée).
            $table->bigIncrements('id');

            // 🇬🇧 Foreign key referencing "workouts" table (nullable).
            // 🇫🇷 Clé étrangère vers la table "workouts" (nullable).
            $table->foreignId('workout_id')->nullable()->constrained('workouts')->onDelete('cascade');

            // 🇬🇧 Foreign key referencing "exercises" table (nullable, sets to null on delete).
            // 🇫🇷 Clé étrangère vers la table "exercises" (nullable, mise à null en cas de suppression).
            $table->foreignId('exercise_id')->nullable()->constrained('exercises')->onDelete('set null');

            // 🇬🇧 Distance of the swim set (optional).
            // 🇫🇷 Distance de la série de natation (optionnelle).
            $table->integer('set_distance')->nullable();

            // 🇬🇧 Number of repetitions (optional).
            // 🇫🇷 Nombre de répétitions (optionnel).
            $table->integer('set_repetition')->nullable();

            // 🇬🇧 Rest time between sets (optional).
            // 🇫🇷 Temps de repos entre les séries (optionnel).
            $table->integer('rest_time')->nullable();

            // 🇬🇧 Timestamps (created_at, updated_at).
            // 🇫🇷 Horodatage (created_at, updated_at).
            $table->timestamps();
        });
    }

    /**
     * 🇬🇧 Reverse the migrations.
     * 🇫🇷 Annuler la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('swim_sets');
    }
};
