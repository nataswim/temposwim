<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * 🇬🇧 Display a listing of the resource.
     * 🇫🇷 Afficher la liste des rôles.
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles, 200); // 🇬🇧 JSON raw response / 🇫🇷 Réponse JSON brute
    }

    /**
     * 🇬🇧 Store a newly created resource in storage.
     * 🇫🇷 Enregistrer un nouveau rôle dans la base de données.
     */
    public function store(Request $request)
    {
        // 🇬🇧 Validate the role name
        // 🇫🇷 Valider le nom du rôle
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|max:255', // 🇬🇧 Must be unique / 🇫🇷 Doit être unique
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $role = Role::create($request->validated());
        return response()->json($role, 201); // 🇬🇧 201 Created / 🇫🇷 201 Créé
    }

    /**
     * 🇬🇧 Display the specified resource.
     * 🇫🇷 Afficher un rôle spécifique.
     */
    public function show(Role $role)
    {
        return response()->json($role, 200);
    }

    /**
     * 🇬🇧 Update the specified resource in storage.
     * 🇫🇷 Mettre à jour un rôle existant.
     */
    public function update(Request $request, Role $role)
    {
        // 🇬🇧 Validate role name update
        // 🇫🇷 Valider la mise à jour du nom du rôle
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $role->id . '|max:255', // 🇬🇧 Must be unique except for the current role / 🇫🇷 Doit être unique sauf pour le rôle actuel
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $role->update($request->validated());
        return response()->json($role, 200);
    }

    /**
     * 🇬🇧 Remove the specified resource from storage.
     * 🇫🇷 Supprimer un rôle spécifique de la base de données.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(null, 204); // 🇬🇧 204 No Content / 🇫🇷 204 Pas de contenu
    }
}
