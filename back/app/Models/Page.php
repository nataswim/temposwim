<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 🇬🇧 Page model representing a content page in the system.
 * 🇫🇷 Modèle Page représentant une page de contenu dans le système.
 */
class Page extends Model
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
        'content',
        'page_category',
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
     * 🇬🇧 Get the user that created the page.
     * 🇫🇷 Récupérer l'utilisateur qui a créé la page.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 🇬🇧 Get the upload associated with the page.
     * 🇫🇷 Récupérer le fichier associé à la page.
     */
    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }
}
