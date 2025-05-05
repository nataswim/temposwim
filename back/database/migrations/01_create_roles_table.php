<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create the "roles" table.
 * 🇫🇷 Migration pour créer la table "roles".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations
     * 🇫🇷 Exécuter la migration
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
    }

    /**
     * 🇬🇧 Reverse the migrations.
     * 🇫🇷 Annuler la migration.
     */
    public function down(): void
    {
        // 🇬🇧 Drop the "roles" table.
        // 🇫🇷 Supprimer la table "roles".
        Schema::dropIfExists('roles');
    }
};