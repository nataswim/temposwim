<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 🇬🇧 WorkoutExerciseSeeder class for populating the "workout_exercises" pivot table.
 * 🇫🇷 Classe WorkoutExerciseSeeder pour le peuplement de la table pivot "workout_exercises".
 */
class WorkoutExerciseSeeder extends Seeder
{
    /**
     * 🇬🇧 Run the database seeds.
     * 🇫🇷 Exécuter le peuplement de la base de données.
     */
    public function run()
    {
        // 🇬🇧 Associate workouts with exercises.
        // 🇫🇷 Associer les séances d'entraînement aux exercices.

        DB::table('workout_exercises')->insert([
            'workout_id' => 1,
            'exercise_id' => 1,
        ]);

        DB::table('workout_exercises')->insert([
            'workout_id' => 2,
            'exercise_id' => 2,
        ]);

        DB::table('workout_exercises')->insert([
            'workout_id' => 3,
            'exercise_id' => 3,
        ]);

        DB::table('workout_exercises')->insert([
            'workout_id' => 4,
            'exercise_id' => 4,
        ]);

        DB::table('workout_exercises')->insert([
            'workout_id' => 5,
            'exercise_id' => 5,
        ]);

        DB::table('workout_exercises')->insert([
            'workout_id' => 6,
            'exercise_id' => 6,
        ]);

        DB::table('workout_exercises')->insert([
            'workout_id' => 7,
            'exercise_id' => 7,
        ]);

        DB::table('workout_exercises')->insert([
            'workout_id' => 8,
            'exercise_id' => 8,
        ]);

        DB::table('workout_exercises')->insert([
            'workout_id' => 9,
            'exercise_id' => 9,
        ]);

        DB::table('workout_exercises')->insert([
            'workout_id' => 10,
            'exercise_id' => 10,
        ]);

        DB::table('workout_exercises')->insert([
            'workout_id' => 11,
            'exercise_id' => 11,
        ]);

        DB::table('workout_exercises')->insert([
            'workout_id' => 12,
            'exercise_id' => 12,
        ]);
    }
}
