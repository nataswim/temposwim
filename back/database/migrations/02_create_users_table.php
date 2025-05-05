<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create the "users" table, along with "password_reset_tokens" and "sessions" tables.
 * 🇫🇷 Migration pour créer la table "users", ainsi que les tables "password_reset_tokens" et "sessions".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations.
     * 🇫🇷 Exécuter la migration.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // 🇬🇧 Primary key (auto-increment).
            // 🇫🇷 Clé primaire (auto-incrémentée).
            $table->id();

            // 🇬🇧 Unique username (nullable).
            // 🇫🇷 Nom d'utilisateur unique (nullable).
            $table->string('username')->unique()->nullable();

            // 🇬🇧 First name (nullable).
            // 🇫🇷 Prénom (nullable).
            $table->string('first_name')->nullable();

            // 🇬🇧 Last name (nullable).
            // 🇫🇷 Nom de famille (nullable).
            $table->string('last_name')->nullable();

            // 🇬🇧 Email (unique).
            // 🇫🇷 Adresse e-mail (unique).
            $table->string('email')->unique();

            // 🇬🇧 Email verification timestamp (nullable).
            // 🇫🇷 Date de vérification de l'e-mail (nullable).
            $table->timestamp('email_verified_at')->nullable();

            // 🇬🇧 Password.
            // 🇫🇷 Mot de passe.
            $table->string('password');

            // 🇬🇧 Token for "Remember Me" functionality.
            // 🇫🇷 Token pour la fonctionnalité "Se souvenir de moi".
            $table->rememberToken();

            // 🇬🇧 Foreign key referencing "roles" table (nullable, sets to null on delete).
            // 🇫🇷 Clé étrangère vers la table "roles" (nullable, mise à null en cas de suppression).
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');

            // 🇬🇧 Timestamps (created_at, updated_at).
            // 🇫🇷 Horodatage (created_at, updated_at).
            $table->timestamps();
        });

        /**
         * 🇬🇧 Create the "password_reset_tokens" table.
         * 🇫🇷 Création de la table "password_reset_tokens".
         */
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            // 🇬🇧 Primary key (email).
            // 🇫🇷 Clé primaire (email).
            $table->string('email')->primary();

            // 🇬🇧 Reset token.
            // 🇫🇷 Token de réinitialisation.
            $table->string('token');

            // 🇬🇧 Timestamp of token creation (nullable).
            // 🇫🇷 Date de création du token (nullable).
            $table->timestamp('created_at')->nullable();
        });

        /**
         * 🇬🇧 Create the "sessions" table.
         * 🇫🇷 Création de la table "sessions".
         */
        Schema::create('sessions', function (Blueprint $table) {
            // 🇬🇧 Primary key (session ID).
            // 🇫🇷 Clé primaire (ID de session).
            $table->string('id')->primary();

            // 🇬🇧 Foreign key referencing "users" table (nullable).
            // 🇫🇷 Clé étrangère vers la table "users" (nullable).
            $table->foreignId('user_id')->nullable()->index();

            // 🇬🇧 IP address of the user (optional, max 45 characters).
            // 🇫🇷 Adresse IP de l'utilisateur (optionnelle, max 45 caractères).
            $table->string('ip_address', 45)->nullable();

            // 🇬🇧 User agent (browser/device details).
            // 🇫🇷 Agent utilisateur (détails du navigateur/appareil).
            $table->text('user_agent')->nullable();

            // 🇬🇧 Session data (payload).
            // 🇫🇷 Données de session (payload).
            $table->longText('payload');

            // 🇬🇧 Last activity timestamp.
            // 🇫🇷 Horodatage de la dernière activité.
            $table->integer('last_activity')->index();
        });
    }

    /**
     * 🇬🇧 Reverse the migrations.
     * 🇫🇷 Annuler la migration.
     */
    public function down(): void
    {
        // 🇬🇧 Drop "users" table.
        // 🇫🇷 Supprimer la table "users".
        Schema::dropIfExists('users');

        // 🇬🇧 Drop "password_reset_tokens" table.
        // 🇫🇷 Supprimer la table "password_reset_tokens".
        Schema::dropIfExists('password_reset_tokens');

        // 🇬🇧 Drop "sessions" table.
        // 🇫🇷 Supprimer la table "sessions".
        Schema::dropIfExists('sessions');
    }
};
