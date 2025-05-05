<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create the "uploads" table.
 * 🇫🇷 Migration pour créer la table "uploads".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations
     * 🇫🇷 Exécuter la migration
     */
    public function up(): void
    {
        Schema::create('uploads', function (Blueprint $table) {
            // 🇬🇧 Primary key (auto-increment).
            // 🇫🇷 Clé primaire (auto-incrémentée).
            $table->bigIncrements('id');

            // 🇬🇧 File name.
            // 🇫🇷 Nom du fichier.
            $table->string('filename');

            // 🇬🇧 File path.
            // 🇫🇷 Chemin du fichier.
            $table->string('path');

            // 🇬🇧 File type (optional).
            // 🇫🇷 Type du fichier (optionnel).
            $table->string('type')->nullable();

            // 🇬🇧 Foreign key referencing "users" table (nullable, sets to null on delete).
            // 🇫🇷 Clé étrangère vers la table "users" (nullable, mise à null en cas de suppression).
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

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
        // 🇬🇧 Drop "uploads" table.
        // 🇫🇷 Supprimer la table "uploads".
        Schema::dropIfExists('uploads');
    }
};