<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;



/**
 * 🇬🇧 User model representing a registered user in the system.
 * 🇫🇷 Modèle User représentant un utilisateur enregistré dans le système.
 */
class User extends Authenticatable implements JWTSubject

{
    use HasFactory, Notifiable;

    /**
     * 🇬🇧 The attributes that are mass assignable.
     * 🇫🇷 Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username', // 🇬🇧 Username / 🇫🇷 Nom d'utilisateur
        'first_name', // 🇬🇧 First name / 🇫🇷 Prénom
        'last_name', // 🇬🇧 Last name / 🇫🇷 Nom de famille
        'role_id', // 🇬🇧 ID of the assigned role / 🇫🇷 ID du rôle assigné
        'email', // 🇬🇧 Email address / 🇫🇷 Adresse e-mail
        'password', // 🇬🇧 Password (hashed) / 🇫🇷 Mot de passe (haché)
    ];

    /**
     * 🇬🇧 The attributes that should be hidden for arrays.
     * 🇫🇷 Les attributs qui doivent être cachés pour les tableaux.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', // 🇬🇧 Hide password / 🇫🇷 Cacher le mot de passe
        'remember_token', // 🇬🇧 Hide remember token / 🇫🇷 Cacher le token de session
    ];

    /**
     * 🇬🇧 The attributes that should be cast.
     * 🇫🇷 Les attributs qui doivent être typés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed', // 🇬🇧 Ensure password is hashed / 🇫🇷 S'assurer que le mot de passe est haché
    ];

    /**
     * 🇬🇧 Get the role assigned to the user.
     * 🇫🇷 Récupérer le rôle assigné à l'utilisateur.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * 🇬🇧 Get the exercises created by the user.
     * 🇫🇷 Récupérer les exercices créés par l'utilisateur.
     */
    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    /**
     * 🇬🇧 Get the pages created by the user.
     * 🇫🇷 Récupérer les pages créées par l'utilisateur.
     */
    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    /**
     * 🇬🇧 Get the training plans created by the user.
     * 🇫🇷 Récupérer les plans d'entraînement créés par l'utilisateur.
     */
    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    /**
     * 🇬🇧 Get the workouts created by the user.
     * 🇫🇷 Récupérer les séances d'entraînement créées par l'utilisateur.
     */
    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }

    /**
     * 🇬🇧 Get the personal lists created by the user.
     * 🇫🇷 Récupérer les listes personnelles créées par l'utilisateur.
     */
    public function mylists()
    {
        return $this->hasMany(Mylist::class);
    }

    /**
     * 🇬🇧 Get the uploaded files associated with the user.
     * 🇫🇷 Récupérer les fichiers téléversés par l'utilisateur.
     *
     * ⚠️ Correction pour Postman :: getUserUploads utilise la relation uploads().
     */
    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
