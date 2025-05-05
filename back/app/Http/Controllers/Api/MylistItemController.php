<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mylist;
use App\Models\MylistItem;
use App\Models\Exercise;
use App\Models\Workout;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MylistItemController extends Controller
{
    /**
     * 🇬🇧 Display a listing of the items for a given mylist.
     * 🇫🇷 Afficher la liste des éléments d'une liste personnelle donnée.
     */
    public function index(Mylist $mylist)
    {
        return response()->json($mylist->mylistItems, 200);
    }

    /**
     * 🇬🇧 Store a new item in a given mylist.
     * 🇫🇷 Ajouter un nouvel élément à une liste personnelle donnée.
     */
    public function store(Request $request, Mylist $mylist)
    {
        Log::info('Nouvelle demande d\'ajout d\'élément à la liste', [
            'mylist_id' => $mylist->id,
            'request_data' => $request->all()
        ]);

        $validator = Validator::make($request->all(), [
            'item_id' => 'required|integer',  // 🇬🇧 Validate the ID / 🇫🇷 Valider l'ID
            'item_type' => 'required|string|max:255', // 🇬🇧 Validate the type / 🇫🇷 Valider le type
        ]);

        if ($validator->fails()) {
            Log::warning('Validation échouée pour l\'ajout d\'élément à la liste', [
                'mylist_id' => $mylist->id,
                'errors' => $validator->errors()
            ]);
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $itemType = $request->input('item_type');
        $itemId = $request->input('item_id');

        Log::info('Traitement de l\'ajout d\'élément à la liste', [
            'mylist_id' => $mylist->id,
            'item_id' => $itemId,
            'item_type' => $itemType
        ]);

        // Conversion du type d'élément en nom de classe complet et vérification de l'existence
        $fullItemType = '';
        $item = null;
        
        switch ($itemType) {
            case 'exercise':
                $item = Exercise::find($itemId);
                $fullItemType = 'App\\Models\\Exercise';
                break;
            case 'workout':
                $item = Workout::find($itemId);
                $fullItemType = 'App\\Models\\Workout';
                break;
            case 'plan':
                $item = Plan::find($itemId);
                $fullItemType = 'App\\Models\\Plan';
                break;
            // Si le type est déjà complet (par exemple App\Models\Exercise)
            default:
                if (strpos($itemType, 'App\\Models\\') === 0) {
                    $className = $itemType;
                    if (class_exists($className)) {
                        $item = $className::find($itemId);
                        $fullItemType = $itemType;
                    } else {
                        Log::error('Type de classe invalide', ['class' => $className]);
                        return response()->json(['error' => 'Type de classe invalide.'], 400);
                    }
                } else {
                    Log::error('Type d\'élément invalide', ['type' => $itemType]);
                    return response()->json(['error' => 'Type d\'élément invalide.'], 400);
                }
        }

        if (!$item) {
            Log::warning('Élément non trouvé', [
                'mylist_id' => $mylist->id, 
                'item_id' => $itemId, 
                'item_type' => $itemType
            ]);
            return response()->json(['error' => 'Élément non trouvé.'], 404);
        }

        // Vérifier si l'élément existe déjà dans la liste
        $existingItem = MylistItem::where('mylist_id', $mylist->id)
            ->where('item_id', $itemId)
            ->where('item_type', $fullItemType)
            ->first();

        if ($existingItem) {
            Log::warning('Élément déjà présent dans la liste', [
                'mylist_id' => $mylist->id, 
                'item_id' => $itemId, 
                'item_type' => $fullItemType
            ]);
            return response()->json(['error' => 'Cet élément est déjà dans la liste.'], 400);
        }

        // Ajouter l'élément à la liste
        $mylistItem = new MylistItem();
        $mylistItem->mylist_id = $mylist->id;
        $mylistItem->item_id = $itemId;
        $mylistItem->item_type = $fullItemType;
        $mylistItem->save();

        Log::info('Élément ajouté à la liste avec succès', [
            'mylist_id' => $mylist->id, 
            'mylist_item_id' => $mylistItem->id, 
            'item_id' => $itemId, 
            'item_type' => $fullItemType
        ]);

        return response()->json($mylistItem, 201);
    }

    /**
     * 🇬🇧 Remove an item from a given mylist.
     * 🇫🇷 Supprimer un élément d'une liste personnelle donnée.
     */
    public function destroy(Mylist $mylist, MylistItem $item)
    {
        Log::info('Demande de suppression d\'élément de la liste', [
            'mylist_id' => $mylist->id, 
            'item_id' => $item->id
        ]);

        // 🇬🇧 Ensure the item belongs to the list
        // 🇫🇷 Vérifier que l'élément appartient bien à la liste
        if ($item->mylist_id !== $mylist->id) {
            Log::warning('Tentative de suppression d\'un élément n\'appartenant pas à la liste', [
                'mylist_id' => $mylist->id, 
                'item_id' => $item->id, 
                'item_mylist_id' => $item->mylist_id
            ]);
            return response()->json(['error' => 'Cet élément n\'appartient pas à cette liste.'], 400);
        }

        $item->delete();
        
        Log::info('Élément supprimé de la liste avec succès', [
            'mylist_id' => $mylist->id, 
            'item_id' => $item->id
        ]);
        
        return response()->json(null, 204);
    }
}