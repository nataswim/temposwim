<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 🇬🇧 SwimSet model representing a set of swimming exercises.
 * 🇫🇷 Modèle SwimSet représentant une série d'exercices de natation.
 */
class SwimSet extends Model
{
    use HasFactory;

    /**
     * 🇬🇧 The attributes that are mass assignable.
     * 🇫🇷 Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'workout_id',
        'exercise_id',
        'set_distance', // 🇬🇧 Distance of the set / 🇫🇷 Distance de la série
        'set_repetition', // 🇬🇧 Number of repetitions / 🇫🇷 Nombre de répétitions
        'rest_time', // 🇬🇧 Rest time between sets / 🇫🇷 Temps de repos entre les séries
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
     * 🇬🇧 Get the workout that the swim set belongs to.
     * 🇫🇷 Récupérer la séance d'entraînement à laquelle appartient la série de natation.
     */
    public function workout()
    {
        return $this->belongsToMany(Workout::class, 'workout_swim_sets');
    }

    /**
     * 🇬🇧 Get the exercise that the swim set uses.
     * 🇫🇷 Récupérer l'exercice utilisé dans cette série de natation.
     */
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
