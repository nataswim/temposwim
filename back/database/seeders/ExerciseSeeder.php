<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Exercise;
use App\Models\User;
use App\Models\Upload;

/**
 * 🇬🇧 Seeder class for populating the "exercises" table.
 * 🇫🇷 Classe Seeder pour le peuplement de la table "exercises".
 */
class ExerciseSeeder extends Seeder
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

        // 🇬🇧 Create exercises for each user.
        // 🇫🇷 Créer des exercices pour chaque utilisateur.
        foreach ($users as $user) {
            Exercise::create([
                'title' => 'Educatif 1',
                'description' => 'Exercice éducatif pour corriger et travailler la dissociation segmentaire.',
                'exercise_level' => 'Intermédiaire',
                'exercise_category' => 'Correctif De Nage',
                'upload_id' => $uploads->random()->id ?? null, // 🇬🇧 Random upload ID if available / 🇫🇷 ID de fichier aléatoire si disponible
                'user_id' => $user->id,
            ]);

            Exercise::create([
                'title' => 'Educatif 2',
                'description' => 'Exercice éducatif pour corriger et travailler la posture et l\'alignement.',
                'exercise_level' => 'Débutant',
                'exercise_category' => 'Correctif De Style',
                'upload_id' => $uploads->random()->id ?? null,
                'user_id' => $user->id,
            ]);

            Exercise::create([
                'title' => 'Battements de Jambes',
                'description' => 'Exercice pour travailler l\'exécution et l\'endurance du bas du corps.',
                'exercise_level' => 'Avancé',
                'exercise_category' => 'Travail de Base',
                'upload_id' => $uploads->random()->id ?? null,
                'user_id' => $user->id,
            ]);
        }
    }
}
