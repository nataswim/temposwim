<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create the "mylist" table.
 * 🇫🇷 Migration pour créer la table "mylist".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations.
     * 🇫🇷 Exécuter la migration.
     */
    public function up(): void
    {
        Schema::create('mylist', function (Blueprint $table) {
            // 🇬🇧 Primary key (auto-increment).
            // 🇫🇷 Clé primaire (auto-incrémentée).
            $table->bigIncrements('id');

            // 🇬🇧 Foreign key referencing "users" table.
            // 🇫🇷 Clé étrangère faisant référence à la table "users".
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // 🇬🇧 Title of the list.
            // 🇫🇷 Titre de la liste.
            $table->string('title');

            // 🇬🇧 Description of the list (optional).
            // 🇫🇷 Description de la liste (optionnelle).
            $table->text('description')->nullable();

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
        Schema::dropIfExists('mylist');
    }
};
