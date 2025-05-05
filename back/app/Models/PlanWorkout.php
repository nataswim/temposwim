<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 🇬🇧 PlanWorkout pivot model (Not needed for now).
 * 🇫🇷 Modèle pivot PlanWorkout (Pas nécessaire pour le moment).
 *
 * 🇬🇧 Laravel automatically manages the many-to-many relationship between plans and workouts.
 * 🇫🇷 Laravel gère automatiquement la relation many-to-many entre les plans et les séances d'entraînement.
 */
class PlanWorkout extends Model
{
    // 🇬🇧 No need for this model at the moment.
    // 🇫🇷 Pas besoin de ce modèle pour le moment.

    // 🇬🇧 There are no additional columns in the pivot table "plan_workouts".
    // 🇫🇷 Il n'y a pas de colonnes supplémentaires dans la table pivot "plan_workouts".

    // 🇬🇧 Laravel automatically handles the many-to-many relationship.
    // 🇫🇷 Laravel gère automatiquement la relation many-to-many.
}
