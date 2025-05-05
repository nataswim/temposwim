<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create the "workout_exercises" pivot table.
 * 🇫🇷 Migration pour créer la table pivot "workout_exercises".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations.
     * 🇫🇷 Exécuter la migration.
     */
    public function up(): void
    {
        Schema::create('workout_exercises', function (Blueprint $table) {
            // 🇬🇧 Foreign key referencing "workouts" table.
            // 🇫🇷 Clé étrangère faisant référence à la table "workouts".
            $table->foreignId('workout_id')->constrained('workouts')->onDelete('cascade');

            // 🇬🇧 Foreign key referencing "exercises" table.
            // 🇫🇷 Clé étrangère faisant référence à la table "exercises".
            $table->foreignId('exercise_id')->constrained('exercises')->onDelete('cascade');

            // 🇬🇧 Composite primary key to ensure unique associations.
            // 🇫🇷 Clé primaire composite pour garantir des associations uniques.
            $table->primary(['workout_id', 'exercise_id']);

            // 🇬🇧 Timestamps (created_at, updated_at) to track association changes.
            // 🇫🇷 Horodatage (created_at, updated_at) pour suivre les modifications des associations.
            $table->timestamps();
        });
    }

    /**
     * 🇬🇧 Reverse the migrations.
     * 🇫🇷 Annuler la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_exercises');
    }
};
