<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workout;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkoutExerciseController extends Controller
{
    /**
     * 🇬🇧 Display a listing of the exercises for a given workout.
     * 🇫🇷 Afficher la liste des exercices associés à une séance d'entraînement donnée.
     */
    public function index(Workout $workout)
    {
        return response()->json($workout->exercises, 200);
    }

    /**
     * 🇬🇧 Attach an exercise to a workout.
     * 🇫🇷 Associer un exercice à une séance d'entraînement.
     */
    public function store(Request $request, Workout $workout)
    {
        // 🇬🇧 Validate the request data
        // 🇫🇷 Valider les données de la requête
        $validator = Validator::make($request->all(), [
            'exercise_id' => 'required|exists:exercises,id', // 🇬🇧 Must reference a valid exercise / 🇫🇷 Doit référencer un exercice valide
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // 🇬🇧 Check if the exercise is already attached
        // 🇫🇷 Vérifier si l'exercice est déjà associé
        if ($workout->exercises()->where('exercise_id', $request->input('exercise_id'))->exists()) {
            return response()->json(['error' => 'Exercise already attached to this workout.'], 400);
        }

        $workout->exercises()->attach($request->input('exercise_id'));

        return response()->json(['message' => 'Exercise attached successfully.'], 201);
    }

    /**
     * 🇬🇧 Display a specific exercise within a workout.
     * 🇫🇷 Afficher un exercice spécifique dans une séance d'entraînement.
     */
    
    public function show(Workout $workout, Exercise $exercise)
    {
        // 🇬🇧 Check if the workout contains the exercise
        // 🇫🇷 Vérifier si la séance contient l'exercice
        if ($workout->exercises->contains($exercise)) {
            return response()->json($exercise, 200);
        } else {
            return response()->json(['message' => 'Exercise not found in workout.'], 404);
        }
    }

    /**
     * 🇬🇧 Detach an exercise from a workout.
     * 🇫🇷 Détacher un exercice d'une séance d'entraînement.
     */

    public function destroy(Workout $workout, Exercise $exercise)
    {
        // 🇬🇧 Check if the association exists before detaching
        // 🇫🇷 Vérifier si l'association existe avant de détacher
        if (!$workout->exercises()->where('exercise_id', $exercise->id)->exists()) {
            return response()->json(['error' => 'Exercise is not attached to this workout.'], 400);
        }

        $workout->exercises()->detach($exercise->id);
        return response()->json(null, 204);
    }
}
