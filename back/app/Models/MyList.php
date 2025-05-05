<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 🇬🇧 Mylist model representing a personal list of exercises, workouts, etc.
 * 🇫🇷 Modèle Mylist représentant une liste personnelle d'exercices, de séances d'entraînement, etc.
 */
class Mylist extends Model
{
    use HasFactory;

    /**
     * 🇬🇧 The attributes that are mass assignable.
     * 🇫🇷 Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
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
     * 🇬🇧 Get the user that owns the mylist.
     * 🇫🇷 Récupérer l'utilisateur propriétaire de la liste personnelle.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 🇬🇧 Get the items in the mylist.
     * 🇫🇷 Récupérer les éléments de la liste personnelle.
     */
    public function mylistItems()
    {
        return $this->hasMany(MylistItem::class); // 🇬🇧 Relationship with MylistItem / 🇫🇷 Relation avec MylistItem
    }
}
