<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\User;
use App\Models\Upload;

/**
 * 🇬🇧 PageSeeder class for populating the "pages" table.
 * 🇫🇷 Classe PageSeeder pour le peuplement de la table "pages".
 */
class PageSeeder extends Seeder
{
    /**
     * 🇬🇧 Run the database seeds.
     * 🇫🇷 Exécuter le peuplement de la base de données.
     */
    public function run(): void
    {
        // 🇬🇧 Retrieve existing users and uploads.
        // 🇫🇷 Récupérer les utilisateurs et les fichiers téléversés existants.
        $users = User::all();
        $uploads = Upload::all();

        // 🇬🇧 If no users exist, display an informational message.
        // 🇫🇷 Si aucun utilisateur n'existe, afficher un message d'information.
        if ($users->isEmpty()) {
            $this->command->info('Pas d\'utilisateur trouvé.');
            return;
        }

        // 🇬🇧 Create pages for each user.
        // 🇫🇷 Créer des pages pour chaque utilisateur.
        foreach ($users as $user) {
            // 🇬🇧 Create an "About" page.
            // 🇫🇷 Créer une page "À propos".
            Page::create([
                'title' => 'À propos',
                'content' => 'Bienvenue sur notre application pour Tous',
                'page_category' => 'Information',
                'upload_id' => $uploads->random()->id ?? null, // 🇬🇧 Assign a random upload if available / 🇫🇷 Assigner un fichier téléversé aléatoire si disponible
                'user_id' => $user->id,
            ]);

            // 🇬🇧 Create a "Terms of Use" page.
            // 🇫🇷 Créer une page "Conditions Générales d’Utilisation (CGU)".
            Page::create([
                'title' => 'CGU - Conditions d\'utilisation',
                'content' => 'Veuillez lire nos conditions d\'utilisation.',
                'page_category' => 'Information',
                'upload_id' => $uploads->random()->id ?? null,
                'user_id' => $user->id,
            ]);

            // 🇬🇧 Create a "Usage Tips" page.
            // 🇫🇷 Créer une page "Conseils d’utilisation".
            Page::create([
                'title' => 'Conseils d\'utilisation',
                'content' => 'Voici quelques conseils pour améliorer vos performances.',
                'page_category' => 'Conseils',
                'upload_id' => $uploads->random()->id ?? null,
                'user_id' => $user->id,
            ]);
        }
    }
}
