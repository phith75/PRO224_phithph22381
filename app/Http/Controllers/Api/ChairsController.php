<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Chairs;
use App\Http\Resources\ChairsResource;
use App\Models\Chairs as ModelsChairs;
use Illuminate\Http\Request;

class ChairsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $data = ModelsChairs::all();
        return ChairsResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Chairs = ModelsChairs::create($request->all());
        return new ChairsResource($Chairs);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Chairs = ModelsChairs::find($id);
        if (!$Chairs) {
            return response()->json(['message' => "Chair not found"], 404);
        }
        return new ChairsResource($Chairs);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Chairs = ModelsChairs::find($id);
        if (!$Chairs) {
            return response()->json(['message' => 'Chair not found'], 404);
        }
        ModelsChairs::where('id', $id)
            ->update($request->except('_token'));

        return new ChairsResource($Chairs);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Chairs = ModelsChairs::find($id);
        if (!$Chairs) {
            return response()->json(['message' => "Chair not found"], 404);
        }
        $Chairs->delete();
        return response()->json(['message' => "Delete success"], 200);
    }
}
