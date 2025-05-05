<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to remove the "old_name" column from the "users" table.
 * 🇫🇷 Migration pour supprimer la colonne "old_name" de la table "users".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations.
     * 🇫🇷 Exécuter la migration.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 🇬🇧 Remove the "old_name" column.
            // 🇫🇷 Supprimer la colonne "old_name".
            $table->dropColumn('old_name');
        });
    }

    /**
     * 🇬🇧 Reverse the migrations.
     * 🇫🇷 Annuler la migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 🇬🇧 Restore the "old_name" column (nullable).
            // 🇫🇷 Restaurer la colonne "old_name" (nullable).
            $table->string('old_name')->nullable();
        });
    }
};
