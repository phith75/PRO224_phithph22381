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
            return response()->json(['message' => "student not found"], 404);
        }
        return new CategoryResource($Categories);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        Categories::where('id', $id)
            ->update($request->except('_token'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        Categories::where('id', $id)
            ->delete();
    }
}
