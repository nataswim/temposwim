<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * 🇬🇧 Migration to modify the "users" table by adding and modifying columns.
 * 🇫🇷 Migration pour modifier la table "users" en ajoutant et modifiant des colonnes.
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
            // 🇬🇧 Add new columns if they do not exist.
            // 🇫🇷 Ajouter les nouvelles colonnes si elles n'existent pas.
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('username');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }

            // 🇬🇧 Rename the 'name' column to 'username' if it exists and 'old_name' does not.
            // 🇫🇷 Renommer la colonne 'name' en 'username' si elle existe et si 'old_name' n'existe pas.
            if (Schema::hasColumn('users', 'name') && !Schema::hasColumn('users', 'old_name')) {
                $table->renameColumn('name', 'old_name');
                $table->renameColumn('old_name', 'username');
            }

            // 🇬🇧 Modify 'role_id' to add foreign key constraint if it does not exist.
            // 🇫🇷 Modifier 'role_id' pour ajouter une contrainte de clé étrangère si elle n'existe pas.
            if (Schema::hasColumn('users', 'role_id')) {
                // 🇬🇧 Check if the constraint already exists.
                // 🇫🇷 Vérifier si la contrainte existe déjà.
                $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                                           WHERE TABLE_NAME = 'users' 
                                           AND COLUMN_NAME = 'role_id' 
                                           AND REFERENCED_TABLE_NAME = 'roles'");

                if (empty($foreignKeys)) {
                    $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null')->change();
                }
            }
        });
    }

    /**
     * 🇬🇧 Reverse the migrations.
     * 🇫🇷 Annuler la migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 🇬🇧 Remove new columns if they exist.
            // 🇫🇷 Supprimer les nouvelles colonnes si elles existent.
            if (Schema::hasColumn('users', 'username')) {
                $table->dropColumn(['username', 'first_name', 'last_name']);
            }

            // 🇬🇧 Remove foreign key constraint from 'role_id' if it exists.
            // 🇫🇷 Supprimer la contrainte de clé étrangère de 'role_id' si elle existe.
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropForeign(['role_id']);
            }

            // 🇬🇧 Rename 'username' back to 'name' if it exists.
            // 🇫🇷 Renommer 'username' en 'name' si elle existe.
            if (Schema::hasColumn('users', 'username')) {
                $table->renameColumn('username', 'name');
            }
        });
    }
};
