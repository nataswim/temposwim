<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 🇬🇧 Exercise model representing a training exercise.
 * 🇫🇷 Modèle Exercise représentant un exercice d'entraînement.
 */
class Exercise extends Model
{
    use HasFactory;

    /**
     * 🇬🇧 The attributes that are mass assignable.
     * 🇫🇷 Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'exercise_level',
        'exercise_category',
        'upload_id',
        'user_id',
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
     * 🇬🇧 Get the user that created the exercise.
     * 🇫🇷 Récupérer l'utilisateur qui a créé l'exercice.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 🇬🇧 Get the upload associated with the exercise.
     * 🇫🇷 Récupérer le fichier associé à l'exercice.
     */
    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }

    /**
     * 🇬🇧 Get the swim sets for the exercise.
     * 🇫🇷 Récupérer les séries de natation associées à l'exercice.
     */
    public function swimSets()
    {
        return $this->hasMany(SwimSet::class);
    }

    /**
     * 🇬🇧 The workouts that belong to the exercise.
     * 🇫🇷 Les séances d'entraînement associées à l'exercice.
     */
    public function workouts()
    {
        return $this->belongsToMany(Workout::class, 'workout_exercises');
    }

    /**
     * 🇬🇧 Get the mylist items associated with this exercise.
     * 🇫🇷 Récupérer les éléments de liste personnelle associés à cet exercice.
     */
    public function mylistItems()
    {
        return $this->morphMany(MylistItem::class, 'item');
    }
}
