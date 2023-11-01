<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryDetailResource;
use App\Models\CategoryDetail;

class CategoryDetailController extends Controller
{
    public function index()
    {
        $CategoryDetail = CategoryDetail::all();
        return CategoryDetailResource::collection($CategoryDetail);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $CategoryDetail = CategoryDetail::create($request->all());
        return new CategoryDetailResource($CategoryDetail);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $CategoryDetail = CategoryDetail::find($id);
        if (!$CategoryDetail) {
            return response()->json(['message' => 'MovieRoom not found'], 404);
        }
        return new CategoryDetailResource($CategoryDetail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $CategoryDetail = CategoryDetail::find($id);
        if (!$CategoryDetail) {
            return response()->json(['message' => 'MovieRoom not found'], 404);
        }
        $CategoryDetail->update($request->all());
        return new CategoryDetailResource($CategoryDetail);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $CategoryDetail = CategoryDetail::find($id);
        if (!$CategoryDetail) {
            return response()->json(['message' => 'CategoryDetail not found'], 404);
        }
        $CategoryDetail->delete();
        return response()->json(['message' => "delete success"], 200);
    }
}
