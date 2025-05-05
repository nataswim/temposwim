<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 🇬🇧 MylistItem model representing an item in a personal list.
 * 🇫🇷 Modèle MylistItem représentant un élément d'une liste personnelle.
 */
class MylistItem extends Model
{
    use HasFactory;

    /**
     * 🇬🇧 The attributes that are mass assignable.
     * 🇫🇷 Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mylist_id',
        'item_id',
        'item_type',
    ];

    /**
     * 🇬🇧 The attributes that should be cast.
     * 🇫🇷 Les attributs qui doivent être typés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * 🇬🇧 Get the mylist that the item belongs to.
     * 🇫🇷 Récupérer la liste personnelle à laquelle appartient l'élément.
     */
    public function mylist()
    {
        return $this->belongsTo(Mylist::class);
    }

    /**
     * 🇬🇧 Get the item (exercise, workout, or plan).
     * 🇫🇷 Récupérer l'élément associé (exercice, séance d'entraînement ou plan).
     */
    public function item()
    {
        return $this->morphTo('item');
    }

    /**
     * 🇬🇧 Get the parent item model (Exercise, Workout, Plan)
     * 🇫🇷 Récupérer le modèle parent de l'élément (Exercise, Workout, Plan)
     */
    public function getItemDetails()
    {
        if (class_exists($this->item_type)) {
            return $this->item_type::find($this->item_id);
        }
        return null;
    }
}