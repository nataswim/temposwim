<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Models\User; // 🇬🇧 Import User model / 🇫🇷 Importation du modèle User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // 🇬🇧 For file management / 🇫🇷 Pour la gestion des fichiers

class UploadController extends Controller
{
    /**
     * 🇬🇧 Display a listing of the resource.
     * 🇫🇷 Afficher la liste des fichiers uploadés.
     */
    public function index()
    {
        $uploads = Upload::all();
        return response()->json($uploads, 200);
    }

    
    
    
    /**
     * 🇬🇧 Store a newly created resource in storage.
     * 🇫🇷 Enregistrer un nouveau fichier dans le stockage.
     */
    public function store(Request $request)
    {
        // 🇬🇧 Validate the upload request
        // 🇫🇷 Valider la requête d'upload
        $validator = Validator::make($request->all(), [
            'filename' => 'required|max:255', // 🇬🇧 Required file name / 🇫🇷 Nom de fichier requis
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:2048', // 🇬🇧 Allowed file types / 🇫🇷 Types de fichiers autorisés
            'type' => 'nullable|max:255', // 🇬🇧 Optional file type / 🇫🇷 Type de fichier facultatif
            'user_id' => 'nullable|exists:users,id', // 🇬🇧 Must reference a valid user / 🇫🇷 Doit référencer un utilisateur valide
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // 🇬🇧 Check if a file is uploaded
        // 🇫🇷 Vérifier si un fichier a été uploadé
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('uploads'); // 🇬🇧 Store file in "uploads" directory / 🇫🇷 Enregistrer le fichier dans le dossier "uploads"


            
            $upload = Upload::create([
                'filename' => $file->getClientOriginalName(), // 🇬🇧 Use original filename / 🇫🇷 Utiliser le nom de fichier original
                'path' => $path,
                'type' => $request->input('type'),
                'user_id' => $request->input('user_id'),
            ]);

            return response()->json($upload, 201);
        }

        return response()->json(['error' => 'No file uploaded'], 400); // 🇬🇧 No file found / 🇫🇷 Aucun fichier trouvé
    }

    
    
    /**
     * 🇬🇧 Display the specified resource.
     * 🇫🇷 Afficher un fichier spécifique.
     */
    public function show(Upload $upload)
    {
        return response()->json($upload, 200);
    }

    
    
    /**
     * 🇬🇧 Update the specified resource in storage.
     * 🇫🇷 Mettre à jour un fichier existant.
     */
    public function update(Request $request, Upload $upload)
    {
        // 🇬🇧 Validate the update request
        // 🇫🇷 Valider la requête de mise à jour
        $validator = Validator::make($request->all(), [
            'filename' => 'required|max:255', // 🇬🇧 Required filename / 🇫🇷 Nom de fichier requis
            'type' => 'nullable|max:255', // 🇬🇧 Optional type / 🇫🇷 Type facultatif
            'user_id' => 'nullable|exists:users,id', // 🇬🇧 Must reference a valid user / 🇫🇷 Doit référencer un utilisateur valide
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $upload->update([
            'filename' => $request->input('filename'),
            'type' => $request->input('type'),
            'user_id' => $request->input('user_id'),
        ]);

        return response()->json($upload, 200);
    }

    
    
    /**
     * 🇬🇧 Remove the specified resource from storage.
     * 🇫🇷 Supprimer un fichier spécifique du stockage.
     */
    public function destroy(Upload $upload)
    {
        // 🇬🇧 Delete the physical file
        // 🇫🇷 Supprimer le fichier physique
        Storage::delete($upload->path);
        $upload->delete();
        return response()->json(null, 204);
    }

   
   
    /**
     * 🇬🇧 Get uploads by user.
     * 🇫🇷 Récupérer les fichiers uploadés par un utilisateur spécifique.
     */
    public function getUserUploads(User $user)
    {
        $uploads = $user->uploads; // 🇬🇧 Use relation defined in User model / 🇫🇷 Utiliser la relation définie dans le modèle User
        return response()->json($uploads, 200);
    }
}
