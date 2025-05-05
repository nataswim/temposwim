<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exercises = Exercise::all();
        return response()->json($exercises, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'nullable',
            'exercise_level' => 'nullable|max:255',
            'exercise_category' => 'nullable|max:255',
            'upload_id' => 'nullable|exists:uploads,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $exercise = Exercise::create($validator->validated());
        return response()->json($exercise, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Exercise $exercise)
    {
        return response()->json($exercise, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $exercise = Exercise::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'nullable',
            'exercise_level' => 'nullable|max:255',
            'exercise_category' => 'nullable|max:255',
            'upload_id' => 'nullable|exists:uploads,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $exercise->update($validator->validated());
        return response()->json($exercise, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exercise $exercise)
    {
        $exercise->delete();
        return response()->json(null, 204);
    }
}