<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mylist;
use App\Models\User;

/**
 * 🇬🇧 MylistSeeder class for populating the "mylists" table.
 * 🇫🇷 Classe MylistSeeder pour le peuplement de la table "mylists".
 */
class MylistSeeder extends Seeder
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
            $this->command->info('Vérifiez les utilisateurs dans la base de données.');
            return;
        }

        // 🇬🇧 Create personal lists for each user.
        // 🇫🇷 Créer des listes personnelles pour chaque utilisateur.
        foreach ($users as $user) {
            // 🇬🇧 Create a list of favorite exercises.
            // 🇫🇷 Créer une liste des exercices favoris.
            Mylist::create([
                'user_id' => $user->id,
                'title' => 'Mes exercices favoris',
                'description' => 'Une liste de mes exercices préférés.',
            ]);

            // 🇬🇧 Create a list of favorite workouts.
            // 🇫🇷 Créer une liste des séances d'entraînement favorites.
            Mylist::create([
                'user_id' => $user->id,
                'title' => 'Mes séances favorites',
                'description' => 'Une liste de mes séances préférées.',
            ]);

            // 🇬🇧 Create a list of favorite plans.
            // 🇫🇷 Créer une liste des plans d'entraînement favoris.
            Mylist::create([
                'user_id' => $user->id,
                'title' => 'Mes plans favoris',
                'description' => 'Une liste de mes plans préférés.',
            ]);
        }
    }
}
