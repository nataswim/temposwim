<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanWorkoutController extends Controller
{
    /**
     * 🇬🇧 Display a listing of the workouts for a given plan.
     * 🇫🇷 Afficher la liste des séances d'entraînement associées à un plan donné.
     */
    public function index(Plan $plan)
    {
        return response()->json($plan->workouts, 200);
    }

    /**
     * 🇬🇧 Attach a workout to a plan.
     * 🇫🇷 Associer une séance d'entraînement à un plan.
     */
    public function store(Request $request, Plan $plan, Workout $workout)
    {
        // 🇬🇧 Validation for attaching a workout to a plan
        // 🇫🇷 Validation pour associer une séance d'entraînement à un plan
        $validator = Validator::make([
            'plan_id' => $plan->id,
            'workout_id' => $workout->id,
        ], [
            'plan_id' => 'exists:plans,id', // 🇬🇧 Must be a valid plan ID / 🇫🇷 Doit être un ID de plan valide
            'workout_id' => 'exists:workouts,id', // 🇬🇧 Must be a valid workout ID / 🇫🇷 Doit être un ID de séance valide
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // 🇬🇧 Check if the workout is already attached
        // 🇫🇷 Vérifier si la séance est déjà associée
        if ($plan->workouts()->where('workout_id', $workout->id)->exists()) {
            return response()->json(['error' => 'Workout already attached to this plan.'], 400);
        }

        $plan->workouts()->attach($workout->id);
        return response()->json(['message' => 'Workout attached successfully.'], 201);
    }

    /**
     * 🇬🇧 Detach a workout from a plan.
     * 🇫🇷 Détacher une séance d'entraînement d'un plan.
     */
    public function destroy(Plan $plan, Workout $workout)
    {
        // 🇬🇧 Check if the workout is attached before detaching
        // 🇫🇷 Vérifier si la séance est associée avant de la détacher
        if (!$plan->workouts()->where('workout_id', $workout->id)->exists()) {
            return response()->json(['error' => 'Workout is not attached to this plan.'], 400);
        }

        $plan->workouts()->detach($workout->id);
        return response()->json(null, 204);
    }

    /**
     * 🇬🇧 Display a specific workout within a plan.
     * 🇫🇷 Afficher une séance spécifique dans un plan.
     */
    public function show(Plan $plan, Workout $workout)
    {
        // 🇬🇧 Check if the plan contains the workout
        // 🇫🇷 Vérifier si le plan contient la séance d'entraînement
        if ($plan->workouts->contains($workout)) {
            return response()->json($workout, 200);
        } else {
            return response()->json(['message' => 'Workout not found in plan.'], 404);
        }
    }
}
