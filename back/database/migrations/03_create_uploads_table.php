<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create the "uploads" table and add foreign keys in related tables.
 * 🇫🇷 Migration pour créer la table "uploads" et ajouter des clés étrangères dans les tables associées.
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations.
     * 🇫🇷 Exécuter la migration.
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

        // 🇬🇧 Add foreign key in "exercises" table referencing "uploads".
        // 🇫🇷 Ajouter une clé étrangère dans la table "exercises" vers "uploads".
        Schema::table('exercises', function (Blueprint $table) {
            $table->foreignId('upload_id')->nullable()->constrained('uploads')->onDelete('set null');
        });

        // 🇬🇧 Add foreign key in "pages" table referencing "uploads".
        // 🇫🇷 Ajouter une clé étrangère dans la table "pages" vers "uploads".
        Schema::table('pages', function (Blueprint $table) {
            $table->foreignId('upload_id')->nullable()->constrained('uploads')->onDelete('set null');
        });
    }

    /**
     * 🇬🇧 Reverse the migrations.
     * 🇫🇷 Annuler la migration.
     */
    public function down(): void
    {
        // 🇬🇧 Remove foreign key from "exercises" table.
        // 🇫🇷 Supprimer la clé étrangère de la table "exercises".
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropForeign(['upload_id']);
        });

        // 🇬🇧 Remove foreign key from "pages" table.
        // 🇫🇷 Supprimer la clé étrangère de la table "pages".
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['upload_id']);
        });

        // 🇬🇧 Drop "uploads" table.
        // 🇫🇷 Supprimer la table "uploads".
        Schema::dropIfExists('uploads');
    }
};
