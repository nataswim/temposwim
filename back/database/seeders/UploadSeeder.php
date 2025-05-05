<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Upload;
use App\Models\User;

/**
 * 🇬🇧 UploadSeeder class for populating the "uploads" table.
 * 🇫🇷 Classe UploadSeeder pour le peuplement de la table "uploads".
 */
class UploadSeeder extends Seeder
{
    /**
     * 🇬🇧 Run the database seeds.
     * 🇫🇷 Exécuter le peuplement de la base de données.
     */
    public function run(): void
    {
        // 🇬🇧 Retrieve existing users.
        // 🇫🇷 Récupérer les utilisateurs existants.
        $users = User::all();

        // 🇬🇧 If no users exist, display an informational message.
        // 🇫🇷 Si aucun utilisateur n'existe, afficher un message d'information.
        if ($users->isEmpty()) {
            $this->command->info('Aucun utilisateur trouvé dans la base de données.');
            return;
        }

        // 🇬🇧 Create file uploads for each user.
        // 🇫🇷 Créer des fichiers téléversés pour chaque utilisateur.
        foreach ($users as $user) {
            // 🇬🇧 First upload (image file).
            // 🇫🇷 Premier téléversement (fichier image).
            Upload::create([
                'filename' => 'example1.jpg', // 🇬🇧 File name / 🇫🇷 Nom du fichier
                'path' => 'uploads/example1.jpg', // 🇬🇧 File path / 🇫🇷 Chemin du fichier
                'type' => 'image', // 🇬🇧 File type / 🇫🇷 Type de fichier
                'user_id' => $user->id, // 🇬🇧 Associated user / 🇫🇷 Utilisateur associé
            ]);

            // 🇬🇧 Second upload (PDF document).
            // 🇫🇷 Deuxième téléversement (document PDF).
            Upload::create([
                'filename' => 'example2.pdf',
                'path' => 'uploads/example2.pdf',
                'type' => 'document',
                'user_id' => $user->id,
            ]);
        }
    }
}
