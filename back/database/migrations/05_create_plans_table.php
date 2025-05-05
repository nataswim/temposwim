<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create the "plans" table.
 * 🇫🇷 Migration pour créer la table "plans".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations.
     * 🇫🇷 Exécuter la migration.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            // 🇬🇧 Primary key (auto-increment).
            // 🇫🇷 Clé primaire (auto-incrémentée).
            $table->bigIncrements('id');

            // 🇬🇧 Title of the training plan.
            // 🇫🇷 Titre du plan d'entraînement.
            $table->string('title');

            // 🇬🇧 Description of the plan (optional).
            // 🇫🇷 Description du plan (optionnelle).
            $table->text('description')->nullable();

            // 🇬🇧 Plan category (optional).
            // 🇫🇷 Catégorie du plan (optionnelle).
            $table->string('plan_category')->nullable();

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
        Schema::dropIfExists('plans');
    }
};
