<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mylist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MylistController extends Controller
{
    /**
     * 🇬🇧 Display a listing of the resource.
     * 🇫🇷 Afficher la liste des listes personnelles.
     */
    public function index(Request $request)
    {
        // Si un user_id est fourni, filtrer les listes par utilisateur
        if ($request->has('user_id')) {
            $userId = $request->input('user_id');
            $mylists = Mylist::where('user_id', $userId)->get();
        } else {
            $mylists = Mylist::all();
        }
        
        return response()->json($mylists, 200);
    }

    /**
     * 🇬🇧 Store a newly created resource in storage.
     * 🇫🇷 Enregistrer une nouvelle liste personnelle dans la base de données.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Uniquement utiliser les données validées
        $data = [
            'user_id' => $request->input('user_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description')
        ];

        $mylist = Mylist::create($data);
        return response()->json($mylist, 201);
    }

    /**
     * 🇬🇧 Display the specified resource.
     * 🇫🇷 Afficher une liste personnelle spécifique.
     */
    public function show(Mylist $mylist)
    {
        return response()->json($mylist, 200);
    }

    /**
     * 🇬🇧 Update the specified resource in storage.
     * 🇫🇷 Mettre à jour une liste personnelle existante.
     */
    public function update(Request $request, Mylist $mylist)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Uniquement utiliser les données validées
        $data = [
            'user_id' => $request->input('user_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description')
        ];

        $mylist->update($data);
        return response()->json($mylist, 200);
    }

    /**
     * 🇬🇧 Remove the specified resource from storage.
     * 🇫🇷 Supprimer une liste personnelle spécifique de la base de données.
     */
    public function destroy(Mylist $mylist)
    {
        $mylist->delete();
        return response()->json(null, 204);
    }

    /**
     * 🇬🇧 Duplicate a mylist.
     * 🇫🇷 Dupliquer une liste personnelle.
     */
    public function duplicate(Mylist $mylist)
    {
        // Créer une nouvelle liste avec les mêmes informations
        $newList = Mylist::create([
            'user_id' => $mylist->user_id,
            'title' => $mylist->title . ' (copie)',
            'description' => $mylist->description
        ]);
        
        // Dupliquer les éléments de la liste
        foreach ($mylist->mylistItems as $item) {
            $newList->mylistItems()->create([
                'item_id' => $item->item_id,
                'item_type' => $item->item_type
            ]);
        }
        
        return response()->json($newList, 201);
    }
}