<?php

namespace App\Http\Controllers\Api;

use App\Models\RateStar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RateStarResource;

class RateStarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $RateStar = RateStar::all();
        return RateStarResource::collection($RateStar);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $RateStar = RateStar::create($request->all());
        return new RateStarResource($RateStar);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $RateStar = RateStar::find($id);
        if(!$RateStar){
            return response()->json(['message'=>'RateStar not found'],404);
        }
        return new RateStarResource($RateStar);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $RateStar = RateStar::find($id);
        if(!$RateStar){
            return response()->json(['message'=>'RateStar not found'],404);
        }
        $RateStar->update($request->all());
        return new RateStarResource($RateStar);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $RateStar = RateStar::find($id);
        if(!$RateStar){
            return response()->json(['message'=>'RateStar not found'],404);
        }
        $RateStar->delete();
        return response()->json(null,204);
    }
}
