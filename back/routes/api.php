<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\WorkoutController;
use App\Http\Controllers\Api\WorkoutExerciseController;
use App\Http\Controllers\Api\MylistController;
use App\Http\Controllers\Api\SwimSetController;
use App\Http\Controllers\Api\MylistItemController;
use App\Http\Controllers\Api\PlanWorkoutController;
use App\Http\Controllers\Api\WorkoutSwimSetController;

// Groupe avec middleware CORS appliqué à toutes les routes API
Route::middleware(['cors'])->group(function () {
    
    // Routes publiques d'authentification
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    
    // Routes publiques pour les pages et uploads (accessibles sans authentification)
    Route::get('pages', [PageController::class, 'index']);
    Route::get('pages/{page}', [PageController::class, 'show']);
    Route::get('uploads/{upload}', [UploadController::class, 'show']);
    
    // Routes protégées qui nécessitent un token JWT
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
        
        // Route Sanctum pour la compatibilité
        Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
            return $request->user();
        });
        
        // ************************************************************************
        // 🇬🇧 Routes for Roles (Rôles)
        // 🇫🇷 Routes pour les rôles (Roles)
        // ************************************************************************
        Route::apiResource('roles', RoleController::class);
        Route::get('roles/{role}/users', [UserController::class, 'getUsersByRole']);
        
        // ************************************************************************
        // 🇬🇧 Routes for Users (Utilisateurs)
        // 🇫🇷 Routes pour les utilisateurs (Users)
        // ************************************************************************
        Route::apiResource('users', UserController::class);
        
        // ************************************************************************
        // 🇬🇧 Routes for File Uploads (Fichiers Uploadés)
        // 🇫🇷 Routes pour les fichiers uploadés (Uploads)
        // ************************************************************************
        Route::apiResource('uploads', UploadController::class)->except(['show']); // Exclure 'show' car il est public
        Route::get('users/{user}/uploads', [UploadController::class, 'getUserUploads']);
        
        // ************************************************************************
        // 🇬🇧 Routes for Pages (Pages)
        // 🇫🇷 Routes pour les pages (Pages)
        // ************************************************************************
        // Méthodes protégées pour les pages (création, mise à jour, suppression)
        Route::post('pages', [PageController::class, 'store']);
        Route::put('pages/{page}', [PageController::class, 'update']);
        Route::patch('pages/{page}', [PageController::class, 'update']);
        Route::delete('pages/{page}', [PageController::class, 'destroy']);
        
        // ************************************************************************
        // 🇬🇧 Routes for Exercises (Exercices)
        // 🇫🇷 Routes pour les exercices (Exercises)
        // ************************************************************************
        Route::apiResource('exercises', ExerciseController::class);
        
        // ************************************************************************
        // 🇬🇧 Routes for Plans (Plans)
        // 🇫🇷 Routes pour les plans (Plans)
        // ************************************************************************
        Route::apiResource('plans', PlanController::class);
        Route::get('plans/{plan}/workouts', [PlanController::class, 'getWorkouts']);
        Route::post('plans/{plan}/workouts/{workout}', [PlanController::class, 'addWorkout']);
        Route::delete('plans/{plan}/workouts/{workout}', [PlanController::class, 'removeWorkout']);
        Route::get('/plans/{plan}/workouts/{workout}', [PlanWorkoutController::class, 'show']);
        
        // ************************************************************************
        // 🇬🇧 Routes for Workouts (Séances d'entraînement)
        // 🇫🇷 Routes pour les séances d'entraînement (Workouts)
        // ************************************************************************
        Route::apiResource('workouts', WorkoutController::class);
        Route::get('workouts/{workout}/exercises', [WorkoutController::class, 'getExercises']);
        Route::get('/workouts/{workout}/exercises/{exercise}', [WorkoutExerciseController::class, 'show']);
        Route::post('workouts/{workout}/exercises/{exercise}', [WorkoutController::class, 'addExercise']);
        Route::delete('workouts/{workout}/exercises/{exercise}', [WorkoutController::class, 'removeExercise']);
        Route::get('workouts/{workout}/swim-sets', [WorkoutController::class, 'getSwimSets']);
        Route::post('workouts/{workout}/swim-sets/{swimSet}', [WorkoutController::class, 'addSwimSet']);
        Route::delete('workouts/{workout}/swim-sets/{swimSet}', [WorkoutController::class, 'removeSwimSet']);
        Route::get('/workouts/{workout}/swim-sets', [WorkoutSwimSetController::class, 'index']);
        Route::get('/workouts/{id}/plans', [WorkoutController::class, 'getPlans']);
        // Route pour obtenir les plans associés à une séance
Route::get('workouts/{id}/plans', [WorkoutController::class, 'getPlans']);
        
        // 12-03-10H Routes pour la gestion des swim-sets dans les workouts
        Route::get('workouts/{workout}/swim-sets', [WorkoutSwimSetController::class, 'index']);
        Route::post('workouts/{workout}/swim-sets/{swimSet}', [WorkoutSwimSetController::class, 'store']);
        Route::delete('workouts/{workout}/swim-sets/{swimSet}', [WorkoutSwimSetController::class, 'destroy']);
        
        // ************************************************************************
        // 🇬🇧 Routes for Swim Sets (Séries de natation)
        // 🇫🇷 Routes pour les séries de natation (Swim Sets)
        // ************************************************************************
        Route::apiResource('swim-sets', SwimSetController::class);
        
        // ************************************************************************
        // 🇬🇧 Routes for Personal Lists (Listes personnelles)
        // 🇫🇷 Routes pour les listes personnelles (Mylists)
        // ************************************************************************
        Route::apiResource('mylists', MylistController::class);
        Route::post('mylists/{mylist}/duplicate', [MylistController::class, 'duplicate']); // Nouvelle route pour la duplication
        Route::get('mylist/{mylist}/items', [MylistItemController::class, 'index']);
        Route::post('mylist/{mylist}/items', [MylistItemController::class, 'store']);
        Route::delete('mylist/{mylist}/items/{item}', [MylistItemController::class, 'destroy']);
    });
});