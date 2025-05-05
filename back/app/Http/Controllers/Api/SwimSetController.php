<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SwimSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SwimSetController extends Controller
{
    /**
     * 🇬🇧 Display a listing of swim sets with exercise details
     * 🇫🇷 Afficher la liste des séries de natation avec les détails des exercices
     */
    public function index()
    {
        // Include the exercise relationship in the query
        $swimSets = SwimSet::with('exercise')->get();
        return response()->json($swimSets, 200);
    }

    /**
     * 🇬🇧 Store a newly created resource in storage
     * 🇫🇷 Enregistrer une nouvelle ressource en base de données
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'workout_id' => 'required|exists:workouts,id',
            'exercise_id' => 'required|exists:exercises,id',
            'set_distance' => 'required|integer|min:1',
            'set_repetition' => 'nullable|integer|min:1',
            'rest_time' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $swimSet = SwimSet::create($validator->validated());
        
        // Load the exercise relationship before returning
        $swimSet->load('exercise');
        
        return response()->json($swimSet, 201);
    }

    /**
     * 🇬🇧 Display the specified resource with exercise details
     * 🇫🇷 Afficher la ressource spécifiée avec les détails de l'exercice
     */
    public function show(SwimSet $swimSet)
    {
        // Load the exercise relationship
        $swimSet->load('exercise');
        return response()->json($swimSet, 200);
    }

    /**
     * 🇬🇧 Update the specified resource in storage
     * 🇫🇷 Mettre à jour la ressource spécifiée en base de données
     */
    public function update(Request $request, $id)
    {
        $swimSet = SwimSet::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'workout_id' => 'required|exists:workouts,id',
            'exercise_id' => 'required|exists:exercises,id',
            'set_distance' => 'required|integer|min:1',
            'set_repetition' => 'nullable|integer|min:1',
            'rest_time' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $swimSet->update($validator->validated());
        
        // Load the exercise relationship before returning
        $swimSet->load('exercise');
        
        return response()->json($swimSet, 200);
    }

    /**
     * 🇬🇧 Remove the specified resource from storage
     * 🇫🇷 Supprimer la ressource spécifiée de la base de données
     */
    public function destroy(SwimSet $swimSet)
    {
        $swimSet->delete();
        return response()->json(null, 204);
    }
}