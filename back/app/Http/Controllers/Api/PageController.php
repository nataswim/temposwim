<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::all();
        return response()->json($pages, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required',
            'page_category' => 'nullable|max:255',
            'upload_id' => 'nullable|exists:uploads,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $page = Page::create($validator->validated());
        return response()->json($page, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return response()->json($page, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required',
            'page_category' => 'nullable|max:255',
            'upload_id' => 'nullable|exists:uploads,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $page->update($validator->validated());
        return response()->json($page, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return response()->json(null, 204);
    }
}