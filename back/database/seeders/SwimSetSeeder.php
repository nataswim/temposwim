<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SwimSet;
use App\Models\Workout;
use App\Models\Exercise;

/**
 * 🇬🇧 SwimSetSeeder class for populating the "swim_sets" table.
 * 🇫🇷 Classe SwimSetSeeder pour le peuplement de la table "swim_sets".
 */
class SwimSetSeeder extends Seeder
{
    /**
     * 🇬🇧 Run the database seeds.
     * 🇫🇷 Exécuter le peuplement de la base de données.
     */
    public function run(): void
    {
        // 🇬🇧 Retrieve existing workouts and exercises.
        // 🇫🇷 Récupérer les séances d'entraînement et les exercices existants.
        $workouts = Workout::all();
        $exercises = Exercise::all();

        // 🇬🇧 If no workouts or exercises exist, display an informational message.
        // 🇫🇷 Si aucun workout ou exercice n'existe, afficher un message d'information.
        if ($workouts->isEmpty() || $exercises->isEmpty()) {
            $this->command->info('Aucun workout ou exercice trouvé. Veuillez les seeder avant.');
            return;
        }

        // 🇬🇧 Create swim sets for each workout and exercise.
        // 🇫🇷 Créer des séries de natation pour chaque séance et exercice.
        foreach ($workouts as $workout) {
            foreach ($exercises as $exercise) {
                // 🇬🇧 First swim set with randomized distance, repetition, and rest time.
                // 🇫🇷 Première série de natation avec distance, répétition et temps de repos aléatoires.
                SwimSet::create([
                    'workout_id' => $workout->id,
                    'exercise_id' => $exercise->id,
                    'set_distance' => rand(25, 200), // 🇬🇧 Distance of the swim set / 🇫🇷 Distance de la série
                    'set_repetition' => rand(1, 10), // 🇬🇧 Number of repetitions / 🇫🇷 Nombre de répétitions
                    'rest_time' => rand(15, 60), // 🇬🇧 Rest time in seconds / 🇫🇷 Temps de repos en secondes
                ]);

                // 🇬🇧 Second swim set with different randomized values.
                // 🇫🇷 Deuxième série de natation avec des valeurs différentes.
                SwimSet::create([
                    'workout_id' => $workout->id,
                    'exercise_id' => $exercise->id,
                    'set_distance' => rand(50, 400),
                    'set_repetition' => rand(1, 5),
                    'rest_time' => rand(30, 90),
                ]);
            }
        }
    }
}
