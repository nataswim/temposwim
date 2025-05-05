<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create the "workouts" table.
 * 🇫🇷 Migration pour créer la table "workouts".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations.
     * 🇫🇷 Exécuter la migration.
     */
    public function up(): void
    {
        Schema::create('workouts', function (Blueprint $table) {
            // 🇬🇧 Primary key (auto-increment).
            // 🇫🇷 Clé primaire (auto-incrémentée).
            $table->bigIncrements('id');

            // 🇬🇧 Title of the workout.
            // 🇫🇷 Titre de la séance d'entraînement.
            $table->string('title');

            // 🇬🇧 Description of the workout (optional).
            // 🇫🇷 Description de la séance d'entraînement (optionnelle).
            $table->text('description')->nullable();

            // 🇬🇧 Workout category (optional).
            // 🇫🇷 Catégorie de la séance d'entraînement (optionnelle).
            $table->string('workout_category')->nullable();

            // 🇬🇧 Foreign key referencing "users" table.
            // 🇫🇷 Clé étrangère vers la table "users".
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

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
        Schema::dropIfExists('workouts');
    }
};
