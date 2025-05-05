<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\User;

/**
 * 🇬🇧 PlanSeeder class for populating the "plans" table.
 * 🇫🇷 Classe PlanSeeder pour le peuplement de la table "plans".
 */
class PlanSeeder extends Seeder
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
            $this->command->info('Aucun utilisateur trouvé. Veuillez d\'abord exécuter le seeder des utilisateurs.');
            return;
        }

        // 🇬🇧 Create training plans for each user.
        // 🇫🇷 Créer des plans d'entraînement pour chaque utilisateur.
        foreach ($users as $user) {
            // 🇬🇧 Create a beginner training plan.
            // 🇫🇷 Créer un plan d'entraînement pour débutants.
            Plan::create([
                'title' => 'Plan d\'entraînement débutant',
                'description' => 'Un plan simple pour les débutants.',
                'plan_category' => 'Débutant',
                'user_id' => $user->id,
            ]);

            // 🇬🇧 Create an intermediate training plan.
            // 🇫🇷 Créer un plan d'entraînement intermédiaire.
            Plan::create([
                'title' => 'Plan d\'entraînement intermédiaire',
                'description' => 'Un plan pour ceux qui ont déjà une expérience.',
                'plan_category' => 'Intermédiaire',
                'user_id' => $user->id,
            ]);

            // 🇬🇧 Create an advanced training plan.
            // 🇫🇷 Créer un plan d'entraînement avancé.
            Plan::create([
                'title' => 'Plan d\'entraînement avancé',
                'description' => 'Un plan intensif pour les athlètes avancés.',
                'plan_category' => 'Avancé',
                'user_id' => $user->id,
            ]);
        }
    }
}
