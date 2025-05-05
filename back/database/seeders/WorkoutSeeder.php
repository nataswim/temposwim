<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Workout;
use App\Models\User;

/**
 * 🇬🇧 WorkoutSeeder class for populating the "workouts" table.
 * 🇫🇷 Classe WorkoutSeeder pour le peuplement de la table "workouts".
 */
class WorkoutSeeder extends Seeder
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

        // 🇬🇧 Create training sessions for each user.
        // 🇫🇷 Créer des séances d'entraînement pour chaque utilisateur.
        foreach ($users as $user) {
            // 🇬🇧 First workout session.
            // 🇫🇷 Première séance d'entraînement.
            Workout::create([
                'title' => 'Séance 1',
                'description' => 'Une séance pour travailler quelque chose.',
                'workout_category' => 'Aero 1', // 🇬🇧 Category: Aerobic training / 🇫🇷 Catégorie : Entraînement aérobie
                'user_id' => $user->id,
            ]);

            // 🇬🇧 Second workout session.
            // 🇫🇷 Deuxième séance d'entraînement.
            Workout::create([
                'title' => 'Séance 2',
                'description' => 'Une séance pour travailler quelque chose.',
                'workout_category' => 'Vitesse', // 🇬🇧 Category: Speed training / 🇫🇷 Catégorie : Entraînement vitesse
                'user_id' => $user->id,
            ]);

            // 🇬🇧 Third workout session.
            // 🇫🇷 Troisième séance d'entraînement.
            Workout::create([
                'title' => 'Séance 3',
                'description' => 'Une séance pour travailler quelque chose.',
                'workout_category' => 'Mixte', // 🇬🇧 Category: Mixed training / 🇫🇷 Catégorie : Entraînement mixte
                'user_id' => $user->id,
            ]);
        }
    }
}
