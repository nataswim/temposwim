<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mylist;
use App\Models\MylistItem;
use App\Models\Exercise;
use App\Models\Workout;
use App\Models\Plan;

/**
 * 🇬🇧 MylistItemSeeder class for populating the "mylist_items" table.
 * 🇫🇷 Classe MylistItemSeeder pour le peuplement de la table "mylist_items".
 */
class MylistItemSeeder extends Seeder
{
    /**
     * 🇬🇧 Run the database seeds.
     * 🇫🇷 Exécuter le peuplement de la base de données.
     */
    public function run(): void
    {
        // 🇬🇧 Retrieve existing personal lists, exercises, workouts, and plans.
        // 🇫🇷 Récupérer les listes personnelles, exercices, séances d'entraînement et plans existants.
        $mylists = Mylist::all();
        $exercises = Exercise::all();
        $workouts = Workout::all();
        $plans = Plan::all();

        // 🇬🇧 If no necessary data exists, display an informational message.
        // 🇫🇷 Si les données nécessaires n'existent pas, afficher un message d'information.
        if ($mylists->isEmpty() || $exercises->isEmpty() || $workouts->isEmpty() || $plans->isEmpty()) {
            $this->command->info('Pas de mylists, exercises, workouts ou plans. Veuillez les seeder avant.');
            return;
        }

        // 🇬🇧 Add items to personal lists.
        // 🇫🇷 Ajouter des éléments aux listes personnelles.
        foreach ($mylists as $mylist) {
            // 🇬🇧 Add an exercise to the list.
            // 🇫🇷 Ajouter un exercice à la liste.
            if ($exercises->isNotEmpty()) {
                MylistItem::create([
                    'mylist_id' => $mylist->id,
                    'item_id' => $exercises->random()->id,
                    'item_type' => 'App\Models\Exercise',
                ]);
            }

            // 🇬🇧 Add a workout to the list.
            // 🇫🇷 Ajouter une séance d'entraînement à la liste.
            if ($workouts->isNotEmpty()) {
                MylistItem::create([
                    'mylist_id' => $mylist->id,
                    'item_id' => $workouts->random()->id,
                    'item_type' => 'App\Models\Workout',
                ]);
            }

            // 🇬🇧 Add a plan to the list.
            // 🇫🇷 Ajouter un plan à la liste.
            if ($plans->isNotEmpty()) {
                MylistItem::create([
                    'mylist_id' => $mylist->id,
                    'item_id' => $plans->random()->id,
                    'item_type' => 'App\Models\Plan',
                ]);
            }
        }
    }
}
