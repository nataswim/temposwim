<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 🇬🇧 WorkoutSwimSet pivot model (Not needed for now).
 * 🇫🇷 Modèle pivot WorkoutSwimSet (Pas nécessaire pour le moment).
 *
 * 🇬🇧 Laravel automatically manages the many-to-many relationship between workouts and swim sets.
 * 🇫🇷 Laravel gère automatiquement la relation many-to-many entre les séances d'entraînement et les séries de natation.
 *
 * 🇬🇧 This model is empty because the pivot table "workout_swim_sets" has no additional columns.
 * 🇫🇷 Ce modèle est vide car la table pivot "workout_swim_sets" ne contient pas de colonnes supplémentaires.
 */
class WorkoutSwimSet extends Model
{
    // 🇬🇧 No need for this model at the moment.
    // 🇫🇷 Pas besoin de ce modèle pour le moment.

    // 🇬🇧 The "workout_swim_sets" table is a simple pivot table.
    // 🇫🇷 La table "workout_swim_sets" est une simple table pivot.

    // 🇬🇧 Laravel automatically handles the many-to-many relationship.
    // 🇫🇷 Laravel gère automatiquement la relation many-to-many.
}
