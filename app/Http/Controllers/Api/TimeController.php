<?php

namespace App\Http\Controllers\Api;

use App\Models\Time;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TimeResource;
use Illuminate\Support\Facades\Validator;

class TimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $time = time::all();
        return TimeResource::collection($time);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:times,time',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $time = time::create($request->all());
        return new TimeResource($time);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $time = time::find($id);
        if (!$time) {
            return response()->json(['message' => 'time not found'], 404);
        }
        return new TimeResource($time);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {   
        $time = time::find($id);
         
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:times,time,'.$id,
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if (!$time) {
            return response()->json(['message' => 'time not found'], 404);
        }
        $time->update($request->all());
        return new TimeResource($time);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $time = time::find($id);
        if (!$time) {
            return response()->json(['message' => 'time not found'], 404);
        }
        $time->delete();
        return response()->json(['message' => "delete success"], 200);
    }
}
