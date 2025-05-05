<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * 🇬🇧 DatabaseSeeder class responsible for seeding the database with initial data.
 * 🇫🇷 Classe DatabaseSeeder responsable du peuplement de la base de données avec des données initiales.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * 🇬🇧 Seed the application's database.
     * 🇫🇷 Peupler la base de données de l'application.
     */
    public function run(): void
    {
        // 🇬🇧 Example of user seeding with factory
        // 🇫🇷 Exemple de création d'utilisateurs avec une factory
        // \App\Models\User::factory(10)->create();

        // 🇬🇧 Example of creating a specific user
        // 🇫🇷 Exemple de création d'un utilisateur spécifique
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            // 🇬🇧 Call individual seeders
            // 🇫🇷 Appeler les seeders individuels

            //OK RoleSeeder::class, // 🇬🇧 Seeding roles / 🇫🇷 Peuplement des rôles
            //OK UserSeeder::class, // 🇬🇧 Seeding users / 🇫🇷 Peuplement des utilisateurs
            //OK UploadSeeder::class, // 🇬🇧 Seeding uploads / 🇫🇷 Peuplement des fichiers téléversés
            //ok ExerciseSeeder::class, // 🇬🇧 Seeding exercises / 🇫🇷 Peuplement des exercices
            //ok PageSeeder::class, // 🇬🇧 Seeding pages / 🇫🇷 Peuplement des pages
            //ok PlanSeeder::class, // 🇬🇧 Seeding plans / 🇫🇷 Peuplement des plans
            //ok WorkoutSeeder::class, // 🇬🇧 Seeding workouts / 🇫🇷 Peuplement des séances d'entraînement
            //ok SwimSetSeeder::class, // 🇬🇧 Seeding swim sets / 🇫🇷 Peuplement des séries de natation
            //ok MylistSeeder::class, // 🇬🇧 Seeding personal lists / 🇫🇷 Peuplement des listes personnelles
            //ok MylistItemSeeder::class, // 🇬🇧 Seeding personal list items / 🇫🇷 Peuplement des éléments de listes personnelles
            //ok PlanWorkoutSeeder::class, // 🇬🇧 Seeding plan-workout relationships / 🇫🇷 Peuplement des relations plan-séance
            //ok WorkoutExerciseSeeder::class, // 🇬🇧 Seeding workout-exercise relationships / 🇫🇷 Peuplement des relations séance-exercice
            //ok WorkoutSwimSetSeeder::class, // 🇬🇧 Seeding workout-swim set relationships / 🇫🇷 Peuplement des relations séance-série de natation
        ]);
    }
}
