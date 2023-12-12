<?php

namespace App\Http\Controllers\Api;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name',
            'slug' => 'required|unique:categories,slug',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,'.$id,
            'slug' => 'required|unique:categories,slug,'.$id,
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
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
