<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 🇬🇧 WorkoutExercise pivot model (Not needed for now).
 * 🇫🇷 Modèle pivot WorkoutExercise (Pas nécessaire pour le moment).
 *
 * 🇬🇧 Laravel automatically manages the many-to-many relationship between workouts and exercises.
 * 🇫🇷 Laravel gère automatiquement la relation many-to-many entre les séances d'entraînement et les exercices.
 */
class WorkoutExercise extends Model
{
    // 🇬🇧 No need for this model at the moment.
    // 🇫🇷 Pas besoin de ce modèle pour le moment.

    // 🇬🇧 There are no additional columns in the pivot table "workout_exercises".
    // 🇫🇷 Il n'y a pas de colonnes supplémentaires dans la table pivot "workout_exercises".

    // 🇬🇧 Laravel automatically handles the many-to-many relationship.
    // 🇫🇷 Laravel gère automatiquement la relation many-to-many.
}