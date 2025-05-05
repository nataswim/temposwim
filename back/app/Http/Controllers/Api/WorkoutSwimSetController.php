<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workout;
use App\Models\SwimSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkoutSwimSetController extends Controller
{
    /**
     * 🇬🇧 Display a listing of the swim sets for a given workout.
     * 🇫🇷 Afficher la liste des séries de natation associées à une séance d'entraînement donnée.
     */
    public function index(Workout $workout)
    {
        return response()->json($workout->swimSets, 200);
    }

    /**
     * 🇬🇧 Attach a swim set to a workout.
     * 🇫🇷 Associer une série de natation à une séance d'entraînement.
     */
    public function store(Request $request, Workout $workout, SwimSet $swimSet)
    {
        // 🇬🇧 Explicit validation
        // 🇫🇷 Validation explicite
        $validator = Validator::make([
            'workout_id' => $workout->id,
            'swim_set_id' => $swimSet->id,
        ], [
            'workout_id' => 'exists:workouts,id', // 🇬🇧 Must be a valid workout ID / 🇫🇷 Doit être un ID de séance valide
            'swim_set_id' => 'exists:swim_sets,id', // 🇬🇧 Must be a valid swim set ID / 🇫🇷 Doit être un ID de série valide
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // 🇬🇧 Check if the association already exists
        // 🇫🇷 Vérifier si l'association existe déjà
        if ($workout->swimSets()->where('swim_set_id', $swimSet->id)->exists()) {
            return response()->json(['error' => 'Swim set already attached to this workout.'], 400);
        }
        // Ajouter la série à la séance
        $workout->swimSets()->attach($swimSet->id);
        return response()->json(['message' => 'Swim set attached successfully.'], 201);
    }

    /**
     * 🇬🇧 Detach a swim set from a workout.
     * 🇫🇷 Détacher une série de natation d'une séance d'entraînement.
     */
    public function destroy(Workout $workout, SwimSet $swimSet)
    {
        // 🇬🇧 Check if the association exists before detaching
        // 🇫🇷 Vérifier si l'association existe avant de détacher
        if (!$workout->swimSets()->where('swim_set_id', $swimSet->id)->exists()) {
            return response()->json(['error' => 'Swim set is not attached to this workout.'], 400);
        }
       // Supprimer la série de la séance
        $workout->swimSets()->detach($swimSet->id);
        return response()->json(null, 204);
    }
}
