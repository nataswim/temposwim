<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role; // 🇬🇧 Import Role model / 🇫🇷 Importer le modèle Role
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash; // 🇬🇧 For password hashing / 🇫🇷 Pour hasher le mot de passe

class UserController extends Controller
{
    /**
     * 🇬🇧 Display a listing of the resource.
     * 🇫🇷 Afficher la liste des utilisateurs.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    /**
     * 🇬🇧 Store a newly created resource in storage.
     * 🇫🇷 Enregistrer un nouvel utilisateur dans la base de données.
     */
    public function store(Request $request)
    {
        // 🇬🇧 Validate the user creation request
        // 🇫🇷 Valider la requête de création d'utilisateur
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users|max:255', // 🇬🇧 Must be unique / 🇫🇷 Doit être unique
            'email' => 'required|email|unique:users|max:255', // 🇬🇧 Must be a valid and unique email / 🇫🇷 Doit être un email valide et unique
            'password' => 'required|min:8', // 🇬🇧 Minimum 8 characters / 🇫🇷 Minimum 8 caractères
            'first_name' => 'nullable|max:255', // 🇬🇧 Optional first name / 🇫🇷 Prénom facultatif
            'last_name' => 'nullable|max:255', // 🇬🇧 Optional last name / 🇫🇷 Nom de famille facultatif
            'role_id' => 'nullable|exists:roles,id', // 🇬🇧 Must reference a valid role / 🇫🇷 Doit référencer un rôle valide
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')), // 🇬🇧 Hash the password / 🇫🇷 Hasher le mot de passe
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'role_id' => $request->input('role_id'),
        ]);

        return response()->json($user, 201);
    }

    /**
     * 🇬🇧 Display the specified resource.
     * 🇫🇷 Afficher un utilisateur spécifique.
     */
    public function show(User $user)
    {
        return response()->json($user, 200);
    }

    /**
     * 🇬🇧 Update the specified resource in storage.
     * 🇫🇷 Mettre à jour un utilisateur existant.
     */
    public function update(Request $request, User $user)
    {
        // 🇬🇧 Validate the user update request
        // 🇫🇷 Valider la requête de mise à jour d'un utilisateur
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255|unique:users,username,' . $user->id, // 🇬🇧 Unique except for current user / 🇫🇷 Unique sauf pour l'utilisateur actuel
            'email' => 'required|email|max:255|unique:users,email,' . $user->id, // 🇬🇧 Email must be unique / 🇫🇷 L'email doit être unique
            'first_name' => 'nullable|max:255', // 🇬🇧 Optional first name / 🇫🇷 Prénom facultatif
            'last_name' => 'nullable|max:255', // 🇬🇧 Optional last name / 🇫🇷 Nom de famille facultatif
            'role_id' => 'nullable|exists:roles,id', // 🇬🇧 Must reference a valid role / 🇫🇷 Doit référencer un rôle valide
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user->update([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'role_id' => $request->input('role_id'),
        ]);

        // 🇬🇧 Handle password update if provided
        // 🇫🇷 Gérer la modification du mot de passe si fourni
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
            $user->save();
        }

        return response()->json($user, 200);
    }

    /**
     * 🇬🇧 Remove the specified resource from storage.
     * 🇫🇷 Supprimer un utilisateur spécifique de la base de données.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }

    /**
     * 🇬🇧 Get users by role.
     * 🇫🇷 Récupérer les utilisateurs par rôle.
     */
    public function getUsersByRole(Role $role)
    {
        $users = $role->users; // 🇬🇧 Use relation defined in Role model / 🇫🇷 Utiliser la relation définie dans le modèle Role
        return response()->json($users, 200);
    }
}
