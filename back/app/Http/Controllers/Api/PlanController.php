<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::all();
        return response()->json($plans, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'nullable',
            'plan_category' => 'nullable|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $plan = Plan::create($validator->validated());
        return response()->json($plan, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        return response()->json($plan, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'nullable',
            'plan_category' => 'nullable|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $plan->update($validator->validated());
        return response()->json($plan, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();
        return response()->json(null, 204);
    }

    /**
     * Get workouts for a plan.
     */
    public function getWorkouts(Plan $plan)
    {
        return response()->json($plan->workouts, 200);
    }

    /**
     * Add workout to plan.
     */
    public function addWorkout(Plan $plan, $workoutId)
    {
        $plan->workouts()->attach($workoutId);
        return response()->json(['message' => 'Workout added successfully'], 201);
    }

    /**
     * Remove workout from plan.
     */
    public function removeWorkout(Plan $plan, $workoutId)
    {
        $plan->workouts()->detach($workoutId);
        return response()->json(null, 204);
    }
}