<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 🇬🇧 Role model representing a user role in the system.
 * 🇫🇷 Modèle Role représentant un rôle utilisateur dans le système.
 */
class Role extends Model
{
    use HasFactory;

    /**
     * 🇬🇧 The attributes that are mass assignable.
     * 🇫🇷 Les attributs qui peuvent être assignés en masse.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * 🇬🇧 The attributes that should be cast.
     * 🇫🇷 Les attributs qui doivent être typés.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * 🇬🇧 Get the users for the role.
     * 🇫🇷 Récupérer les utilisateurs associés à ce rôle.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
