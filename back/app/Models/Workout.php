<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 🇬🇧 Workout model representing a training session.
 * 🇫🇷 Modèle Workout représentant une séance d'entraînement.
 */
class Workout extends Model
{
    use HasFactory;

    /**
     * 🇬🇧 The attributes that are mass assignable.
     * 🇫🇷 Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', // 🇬🇧 Workout title / 🇫🇷 Titre de la séance
        'description', // 🇬🇧 Workout description / 🇫🇷 Description de la séance
        'workout_category', // 🇬🇧 Workout category / 🇫🇷 Catégorie de la séance
        'user_id', // 🇬🇧 ID of the user who created the workout / 🇫🇷 ID de l'utilisateur ayant créé la séance
    ];

    /**
     * 🇬🇧 The attributes that should be cast.
     * 🇫🇷 Les attributs qui doivent être typés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * 🇬🇧 Get the user that created the workout.
     * 🇫🇷 Récupérer l'utilisateur qui a créé la séance d'entraînement.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 🇬🇧 The exercises that belong to the workout.
     * 🇫🇷 Les exercices associés à cette séance d'entraînement.
     */
    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'workout_exercises');
    }

    /**
     * 🇬🇧 The plans that the workout is included in.
     * 🇫🇷 Les plans dans lesquels cette séance d'entraînement est incluse.
     */
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_workouts');
    }

    /**
     * 🇬🇧 Get the swim sets for the workout.
     * 🇫🇷 Récupérer les séries de natation associées à cette séance.
     */
    public function swimSets()
    {
        return $this->belongsToMany(SwimSet::class, 'workout_swim_sets');
    }

    /**
     * 🇬🇧 Get the mylist items associated with this workout.
     * 🇫🇷 Récupérer les éléments de liste personnelle associés à cette séance.
     */
    public function mylistItems()
    {
        return $this->morphMany(MylistItem::class, 'item');
    }
}
