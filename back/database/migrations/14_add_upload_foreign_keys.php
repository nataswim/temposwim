<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * 🇬🇧 Migration to add upload foreign keys to related tables.
 * 🇫🇷 Migration pour ajouter des clés étrangères d'upload aux tables associées.
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migration
     * 🇫🇷 Exécuter la migration
     */
    public function up(): void
    {
        // Vérifier si la contrainte existe déjà pour exercises
        $exercisesConstraintExists = $this->constraintExists('exercises', 'exercises_upload_id_foreign');
        if (!$exercisesConstraintExists) {
            Schema::table('exercises', function (Blueprint $table) {
                // Vérifier si la colonne existe
                if (Schema::hasColumn('exercises', 'upload_id')) {
                    try {
                        // Utiliser un nom différent pour éviter les conflits
                        $table->foreign('upload_id', 'ex_upload_fk')->references('id')->on('uploads')->onDelete('set null');
                    } catch (\Exception $e) {
                        // Ignorer l'erreur
                    }
                }
            });
        }

        // Vérifier si la contrainte existe déjà pour pages
        $pagesConstraintExists = $this->constraintExists('pages', 'pages_upload_id_foreign');
        if (!$pagesConstraintExists) {
            Schema::table('pages', function (Blueprint $table) {
                // Vérifier si la colonne existe
                if (Schema::hasColumn('pages', 'upload_id')) {
                    try {
                        // Utiliser un nom différent pour éviter les conflits
                        $table->foreign('upload_id', 'pg_upload_fk')->references('id')->on('uploads')->onDelete('set null');
                    } catch (\Exception $e) {
                        // Ignorer l'erreur
                    }
                }
            });
        }
    }

    /**
     * 🇬🇧 Reverse the migrations.
     * 🇫🇷 Annuler la migration.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            if ($this->constraintExists('exercises', 'exercises_upload_id_foreign')) {
                $table->dropForeign(['upload_id']);
            } else if ($this->constraintExists('exercises', 'ex_upload_fk')) {
                $table->dropForeign('ex_upload_fk');
            }
        });

        Schema::table('pages', function (Blueprint $table) {
            if ($this->constraintExists('pages', 'pages_upload_id_foreign')) {
                $table->dropForeign(['upload_id']);
            } else if ($this->constraintExists('pages', 'pg_upload_fk')) {
                $table->dropForeign('pg_upload_fk');
            }
        });
    }

    /**
     * 🇬🇧 Check if a constraint exists.
     * 🇫🇷 Vérifier si une contrainte existe.
     */
    private function constraintExists($table, $constraintName)
    {
        $database = config('database.connections.mysql.database');
        $result = DB::select("
            SELECT COUNT(*) as count
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = '{$database}'
            AND TABLE_NAME = '{$table}'
            AND CONSTRAINT_NAME = '{$constraintName}'
        ");
        
        return $result[0]->count > 0;
    }
};