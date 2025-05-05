<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create the "roles" table and add a foreign key in the "users" table.
 * 🇫🇷 Migration pour créer la table "roles" et ajouter une clé étrangère dans la table "users".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations.
     * 🇫🇷 Exécuter la migration.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            // 🇬🇧 Primary key (auto-increment).
            // 🇫🇷 Clé primaire (auto-incrémentée).
            $table->bigIncrements('id');

            // 🇬🇧 Role name (unique).
            // 🇫🇷 Nom du rôle (unique).
            $table->string('name')->unique();

            // 🇬🇧 Timestamps (created_at, updated_at).
            // 🇫🇷 Horodatage (created_at, updated_at).
            $table->timestamps();
        });

        // 🇬🇧 Add foreign key in "users" table referencing "roles".
        // 🇫🇷 Ajouter une clé étrangère dans la table "users" vers "roles".
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
        });
    }

    /**
     * 🇬🇧 Reverse the migrations.
     * 🇫🇷 Annuler la migration.
     */
    public function down(): void
    {
        // 🇬🇧 Drop foreign key constraint before dropping the "roles" table.
        // 🇫🇷 Supprimer la contrainte de clé étrangère avant de supprimer la table "roles".
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });

        // 🇬🇧 Drop the "roles" table.
        // 🇫🇷 Supprimer la table "roles".
        Schema::dropIfExists('roles');
    }
};
