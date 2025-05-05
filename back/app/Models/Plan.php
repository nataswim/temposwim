<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 🇬🇧 Plan model representing a training plan in the system.
 * 🇫🇷 Modèle Plan représentant un plan d'entraînement dans le système.
 */
class Plan extends Model
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
        'plan_category',
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
     * 🇬🇧 Get the user that created the plan.
     * 🇫🇷 Récupérer l'utilisateur qui a créé le plan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 🇬🇧 The workouts that belong to the plan.
     * 🇫🇷 Les séances d'entraînement associées à ce plan.
     */
    public function workouts()
    {
        return $this->belongsToMany(Workout::class, 'plan_workouts');
    }

    /**
     * 🇬🇧 Get the mylist items associated with this plan.
     * 🇫🇷 Récupérer les éléments de liste personnelle associés à ce plan.
     */
    public function mylistItems()
    {
        return $this->morphMany(MylistItem::class, 'item');
    }
}
