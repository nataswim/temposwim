<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create job-related tables: "jobs", "job_batches", and "failed_jobs".
 * 🇫🇷 Migration pour créer les tables liées aux tâches: "jobs", "job_batches" et "failed_jobs".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations.
     * 🇫🇷 Exécuter la migration.
     */
    public function up(): void
    {
        /**
         * 🇬🇧 Create the "jobs" table for managing queued jobs.
         * 🇫🇷 Création de la table "jobs" pour gérer les tâches en file d'attente.
         */
        Schema::create('jobs', function (Blueprint $table) {
            // 🇬🇧 Primary key (auto-increment).
            // 🇫🇷 Clé primaire (auto-incrémentée).
            $table->id();

            // 🇬🇧 Queue name (indexed).
            // 🇫🇷 Nom de la file d'attente (indexé).
            $table->string('queue')->index();

            // 🇬🇧 Serialized job payload.
            // 🇫🇷 Charge utile sérialisée de la tâche.
            $table->longText('payload');

            // 🇬🇧 Number of attempts made to process the job.
            // 🇫🇷 Nombre de tentatives pour traiter la tâche.
            $table->unsignedTinyInteger('attempts');

            // 🇬🇧 Timestamp when the job was reserved (nullable).
            // 🇫🇷 Horodatage lorsque la tâche a été réservée (nullable).
            $table->unsignedInteger('reserved_at')->nullable();

            // 🇬🇧 Timestamp when the job becomes available.
            // 🇫🇷 Horodatage lorsque la tâche devient disponible.
            $table->unsignedInteger('available_at');

            // 🇬🇧 Timestamp when the job was created.
            // 🇫🇷 Horodatage lorsque la tâche a été créée.
            $table->unsignedInteger('created_at');
        });

        /**
         * 🇬🇧 Create the "job_batches" table for tracking batch jobs.
         * 🇫🇷 Création de la table "job_batches" pour suivre les lots de tâches.
         */
        Schema::create('job_batches', function (Blueprint $table) {
            // 🇬🇧 Primary key (batch ID).
            // 🇫🇷 Clé primaire (ID du lot).
            $table->string('id')->primary();

            // 🇬🇧 Batch name.
            // 🇫🇷 Nom du lot de tâches.
            $table->string('name');

            // 🇬🇧 Total jobs in the batch.
            // 🇫🇷 Nombre total de tâches dans le lot.
            $table->integer('total_jobs');

            // 🇬🇧 Number of pending jobs.
            // 🇫🇷 Nombre de tâches en attente.
            $table->integer('pending_jobs');

            // 🇬🇧 Number of failed jobs.
            // 🇫🇷 Nombre de tâches échouées.
            $table->integer('failed_jobs');

            // 🇬🇧 IDs of failed jobs.
            // 🇫🇷 Identifiants des tâches échouées.
            $table->longText('failed_job_ids');

            // 🇬🇧 Additional batch options (nullable).
            // 🇫🇷 Options supplémentaires pour le lot (nullable).
            $table->mediumText('options')->nullable();

            // 🇬🇧 Timestamp when the batch was cancelled (nullable).
            // 🇫🇷 Horodatage lorsque le lot a été annulé (nullable).
            $table->integer('cancelled_at')->nullable();

            // 🇬🇧 Timestamp when the batch was created.
            // 🇫🇷 Horodatage lorsque le lot a été créé.
            $table->integer('created_at');

            // 🇬🇧 Timestamp when the batch was finished (nullable).
            // 🇫🇷 Horodatage lorsque le lot a été terminé (nullable).
            $table->integer('finished_at')->nullable();
        });

        /**
         * 🇬🇧 Create the "failed_jobs" table for storing failed job details.
         * 🇫🇷 Création de la table "failed_jobs" pour stocker les détails des tâches échouées.
         */
        Schema::create('failed_jobs', function (Blueprint $table) {
            // 🇬🇧 Primary key (auto-increment).
            // 🇫🇷 Clé primaire (auto-incrémentée).
            $table->id();

            // 🇬🇧 Unique job UUID.
            // 🇫🇷 UUID unique de la tâche.
            $table->string('uuid')->unique();

            // 🇬🇧 Connection name where the job was executed.
            // 🇫🇷 Nom de la connexion où la tâche a été exécutée.
            $table->text('connection');

            // 🇬🇧 Queue name.
            // 🇫🇷 Nom de la file d'attente.
            $table->text('queue');

            // 🇬🇧 Serialized job payload.
            // 🇫🇷 Charge utile sérialisée de la tâche.
            $table->longText('payload');

            // 🇬🇧 Exception message for the failed job.
            // 🇫🇷 Message d'exception pour la tâche échouée.
            $table->longText('exception');

            // 🇬🇧 Timestamp when the job failed.
            // 🇫🇷 Horodatage lorsque la tâche a échoué.
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * 🇬🇧 Reverse the migrations.
     * 🇫🇷 Annuler la migration.
     */
    public function down(): void
    {
        // 🇬🇧 Drop the "jobs" table.
        // 🇫🇷 Supprimer la table "jobs".
        Schema::dropIfExists('jobs');

        // 🇬🇧 Drop the "job_batches" table.
        // 🇫🇷 Supprimer la table "job_batches".
        Schema::dropIfExists('job_batches');

        // 🇬🇧 Drop the "failed_jobs" table.
        // 🇫🇷 Supprimer la table "failed_jobs".
        Schema::dropIfExists('failed_jobs');
    }
};
