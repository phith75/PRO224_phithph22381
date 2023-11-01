<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Categories::all();
        return CategoryResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Categories::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Categories = Categories::find($id);
        if (!$Categories) {
            return response()->json(['message' => "category not found"], 404);
        }
        return new CategoryResource($Categories);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categories = Categories::find($id);
        if (!$categories) {
            return response()->json(['message' => 'category not found'], 404);
        }
        $categories->update($request->all());
        return new CategoryResource($categories);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Categories = Categories::find($id);
        if (!$Categories) {
            return response()->json(['message' => "category not found"], 404);
        }
        $Categories->delete();
        return response()->json(['message' => "delete success"], 200);
    }
}
