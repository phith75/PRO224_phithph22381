<?php

namespace App\Http\Controllers\Api;

use App\Models\Food;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Food::all();
       
        return FoodResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:food,name',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $Food = Food::create($request->all());

        return new FoodResource($Food);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Food = Food::find($id);
        
        if (!$Food) {
            return response()->json(['message' => "food not found"], 404);
        }
        return new FoodResource($Food);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $Food = Food::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:food,name,'.$id,
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if (!$Food) {
            return response()->json(['message' => 'Food not found'], 404);
        }
        $Food->update($request->except('_token'));

        return new FoodResource($Food);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $Food = Food::find($id);
        if (!$Food) {
            return response()->json(['message' => 'Food not found'], 404);
        }
        $Food->delete();
        return response()->json(['message' => "delete success"], 200);
    }
}
