<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create the "pages" table.
 * 🇫🇷 Migration pour créer la table "pages".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations.
     * 🇫🇷 Exécuter la migration.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            // 🇬🇧 Primary key (auto-increment).
            // 🇫🇷 Clé primaire (auto-incrémentée).
            $table->bigIncrements('id');

            // 🇬🇧 Title of the page.
            // 🇫🇷 Titre de la page.
            $table->string('title');

            // 🇬🇧 Content of the page.
            // 🇫🇷 Contenu de la page.
            $table->longText('content');

            // 🇬🇧 Page category (optional).
            // 🇫🇷 Catégorie de la page (optionnelle).
            $table->string('page_category')->nullable();

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
        Schema::dropIfExists('pages');
    }
};
