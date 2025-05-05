<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create the "exercises" table.
 * 🇫🇷 Migration pour créer la table "exercises".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations.
     * 🇫🇷 Exécuter la migration.
     */
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            // 🇬🇧 Primary key (auto-increment).
            // 🇫🇷 Clé primaire (auto-incrémentée).
            $table->bigIncrements('id');

            // 🇬🇧 Title of the exercise.
            // 🇫🇷 Titre de l'exercice.
            $table->string('title');

            // 🇬🇧 Description of the exercise (optional).
            // 🇫🇷 Description de l'exercice (optionnelle).
            $table->text('description')->nullable();

            // 🇬🇧 Exercise level (optional).
            // 🇫🇷 Niveau de l'exercice (optionnel).
            $table->string('exercise_level')->nullable();

            // 🇬🇧 Exercise category (optional).
            // 🇫🇷 Catégorie de l'exercice (optionnelle).
            $table->string('exercise_category')->nullable();

            // 🇬🇧 Foreign key referencing "uploads" table (nullable, sets to null on delete).
            // 🇫🇷 Clé étrangère vers la table "uploads" (nullable, mise à null en cas de suppression).
            $table->foreignId('upload_id')->nullable()->constrained('uploads')->onDelete('set null');

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
        Schema::dropIfExists('exercises');
    }
};
