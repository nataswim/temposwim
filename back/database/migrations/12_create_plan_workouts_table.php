<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create the "plan_workouts" pivot table.
 * 🇫🇷 Migration pour créer la table pivot "plan_workouts".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations.
     * 🇫🇷 Exécuter la migration.
     */
    public function up(): void
    {
        Schema::create('plan_workouts', function (Blueprint $table) {
            // 🇬🇧 Foreign key referencing "plans" table.
            // 🇫🇷 Clé étrangère faisant référence à la table "plans".
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');

            // 🇬🇧 Foreign key referencing "workouts" table.
            // 🇫🇷 Clé étrangère faisant référence à la table "workouts".
            $table->foreignId('workout_id')->constrained('workouts')->onDelete('cascade');

            // 🇬🇧 Composite primary key to ensure unique associations.
            // 🇫🇷 Clé primaire composite pour garantir des associations uniques.
            $table->primary(['plan_id', 'workout_id']);

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
        Schema::dropIfExists('plan_workouts');
    }
};
