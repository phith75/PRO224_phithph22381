<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TimeDetail;
use App\Http\Resources\TimeDetailResource;

class Time_detailController extends Controller
{
    public function index()
    {
        $TimeDetail = TimeDetail::all();
        return TimeDetailResource::collection($TimeDetail);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $TimeDetail = TimeDetail::create($request->all());
        return new TimeDetailResource($TimeDetail);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $TimeDetail = TimeDetail::find($id);
        if (!$TimeDetail) {
            return response()->json(['message' => 'MovieRoom not found'], 404);
        }
        return new TimeDetailResource($TimeDetail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $TimeDetail = TimeDetail::find($id);
        if (!$TimeDetail) {
            return response()->json(['message' => 'MovieRoom not found'], 404);
        }
        $TimeDetail->update($request->all());
        return new TimeDetailResource($TimeDetail);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $TimeDetail = TimeDetail::find($id);
        if (!$TimeDetail) {
            return response()->json(['message' => 'TimeDetail not found'], 404);
        }
        $TimeDetail->delete();
        return response()->json(null, 204);
    }
}
