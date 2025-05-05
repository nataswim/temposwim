<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 🇬🇧 Upload model representing a file uploaded by a user.
 * 🇫🇷 Modèle Upload représentant un fichier téléversé par un utilisateur.
 */

class Upload extends Model
{
    use HasFactory;

    /**
     * 🇬🇧 The attributes that are mass assignable.
     * 🇫🇷 Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'filename', // 🇬🇧 File name / 🇫🇷 Nom du fichier
        'path', // 🇬🇧 File storage path / 🇫🇷 Chemin de stockage du fichier
        'type', // 🇬🇧 File type (image, document, etc.) / 🇫🇷 Type de fichier (image, document, etc.)
        'user_id', // 🇬🇧 ID of the user who uploaded the file / 🇫🇷 ID de l'utilisateur qui a téléversé le fichier
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
     * 🇬🇧 Get the user that uploaded the file.
     * 🇫🇷 Récupérer l'utilisateur qui a téléversé le fichier.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 🇬🇧 Get the exercises associated with this upload.
     * 🇫🇷 Récupérer les exercices associés à ce fichier.
     */
    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    /**
     * 🇬🇧 Get the pages associated with this upload.
     * 🇫🇷 Récupérer les pages associées à ce fichier.
     */
    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    /**
     * 🇬🇧 Get all uploads for a given user.
     * 🇫🇷 Récupérer tous les fichiers téléversés par un utilisateur donné.
     *
     * Correction Suite erreur sur Postman :: getUserUploads utilise la relation uploads() de User.
     */
    public function getUserUploads(User $user)
    {
        $uploads = $user->uploads;
        return response()->json($uploads, 200);
    }
}
