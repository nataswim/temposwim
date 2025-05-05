<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 🇬🇧 WorkoutSwimSetSeeder class for populating the "workout_swim_sets" pivot table.
 * 🇫🇷 Classe WorkoutSwimSetSeeder pour le peuplement de la table pivot "workout_swim_sets".
 */
class WorkoutSwimSetSeeder extends Seeder
{
    /**
     * 🇬🇧 Run the database seeds.
     * 🇫🇷 Exécuter le peuplement de la base de données.
     */
    public function run()
    {
        // 🇬🇧 Associate workouts with swim sets.
        // 🇫🇷 Associer les séances d'entraînement aux séries de natation.

        $associations = [
            ['workout_id' => 1, 'swim_set_id' => 1],
            ['workout_id' => 2, 'swim_set_id' => 2],
            ['workout_id' => 3, 'swim_set_id' => 3],
            ['workout_id' => 4, 'swim_set_id' => 4],
            ['workout_id' => 5, 'swim_set_id' => 5],
            ['workout_id' => 6, 'swim_set_id' => 6],
            ['workout_id' => 7, 'swim_set_id' => 7],
            ['workout_id' => 8, 'swim_set_id' => 8],
            ['workout_id' => 9, 'swim_set_id' => 9],
            ['workout_id' => 10, 'swim_set_id' => 10],
            ['workout_id' => 11, 'swim_set_id' => 11],
            ['workout_id' => 12, 'swim_set_id' => 12],
            ['workout_id' => 1, 'swim_set_id' => 13],
            ['workout_id' => 2, 'swim_set_id' => 14],
            ['workout_id' => 3, 'swim_set_id' => 15],
            ['workout_id' => 4, 'swim_set_id' => 16],
            ['workout_id' => 5, 'swim_set_id' => 17],
            ['workout_id' => 6, 'swim_set_id' => 18],
            ['workout_id' => 7, 'swim_set_id' => 19],
            ['workout_id' => 8, 'swim_set_id' => 20],
            ['workout_id' => 9, 'swim_set_id' => 21],
            ['workout_id' => 10, 'swim_set_id' => 22],
            ['workout_id' => 11, 'swim_set_id' => 23],
            ['workout_id' => 12, 'swim_set_id' => 24],
            ['workout_id' => 12, 'swim_set_id' => 25],
            ['workout_id' => 1, 'swim_set_id' => 26],
            ['workout_id' => 2, 'swim_set_id' => 27],
            ['workout_id' => 3, 'swim_set_id' => 28],
            ['workout_id' => 4, 'swim_set_id' => 29],
            ['workout_id' => 5, 'swim_set_id' => 30],
            ['workout_id' => 6, 'swim_set_id' => 31],
            ['workout_id' => 7, 'swim_set_id' => 32],
            ['workout_id' => 8, 'swim_set_id' => 33],
        ];

        // 🇬🇧 Insert the associations into the database.
        // 🇫🇷 Insérer les associations dans la base de données.
        DB::table('workout_swim_sets')->insert($associations);
    }
}
