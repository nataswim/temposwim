<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 🇬🇧 PlanWorkoutSeeder class for populating the "plan_workouts" pivot table.
 * 🇫🇷 Classe PlanWorkoutSeeder pour le peuplement de la table pivot "plan_workouts".
 */
class PlanWorkoutSeeder extends Seeder
{
    /**
     * 🇬🇧 Run the database seeds.
     * 🇫🇷 Exécuter le peuplement de la base de données.
     *
     * @return void
     */
    public function run()
    {
        // 🇬🇧 Associate plans with workouts.
        // 🇫🇷 Associer des plans à des séances d'entraînement.
        DB::table('plan_workouts')->insert([
            'plan_id' => 1,
            'workout_id' => 1,
        ]);

        DB::table('plan_workouts')->insert([
            'plan_id' => 2,
            'workout_id' => 2,
        ]);

        DB::table('plan_workouts')->insert([
            'plan_id' => 3,
            'workout_id' => 3,
        ]);

        DB::table('plan_workouts')->insert([
            'plan_id' => 4,
            'workout_id' => 4,
        ]);

        DB::table('plan_workouts')->insert([
            'plan_id' => 5,
            'workout_id' => 5,
        ]);

        DB::table('plan_workouts')->insert([
            'plan_id' => 6,
            'workout_id' => 6,
        ]);

        DB::table('plan_workouts')->insert([
            'plan_id' => 7,
            'workout_id' => 7,
        ]);

        DB::table('plan_workouts')->insert([
            'plan_id' => 8,
            'workout_id' => 8,
        ]);

        DB::table('plan_workouts')->insert([
            'plan_id' => 9,
            'workout_id' => 9,
        ]);

        DB::table('plan_workouts')->insert([
            'plan_id' => 10,
            'workout_id' => 10,
        ]);

        DB::table('plan_workouts')->insert([
            'plan_id' => 11,
            'workout_id' => 11,
        ]);

        DB::table('plan_workouts')->insert([
            'plan_id' => 12,
            'workout_id' => 12,
        ]);
    }
}
