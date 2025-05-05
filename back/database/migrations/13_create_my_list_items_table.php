<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🇬🇧 Migration to create the "mylist_items" table.
 * 🇫🇷 Migration pour créer la table "mylist_items".
 */
return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations.
     * 🇫🇷 Exécuter la migration.
     */
    public function up(): void
    {
        Schema::create('mylist_items', function (Blueprint $table) {
            // 🇬🇧 Primary key (auto-increment).
            // 🇫🇷 Clé primaire (auto-incrémentée).
            $table->bigIncrements('id');

            // 🇬🇧 Foreign key referencing "mylist" table.
            // 🇫🇷 Clé étrangère faisant référence à la table "mylist".
            $table->foreignId('mylist_id')->constrained('mylist')->onDelete('cascade');

            // 🇬🇧 Polymorphic relationship: ID of the associated item.
            // 🇫🇷 Relation polymorphique : ID de l'élément associé.
            $table->bigInteger('item_id');

            // 🇬🇧 Polymorphic relationship: Type of the associated item.
            // 🇫🇷 Relation polymorphique : Type de l'élément associé.
            $table->string('item_type');

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
        Schema::dropIfExists('mylist_items');
    }
};
