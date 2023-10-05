<?php

namespace App\Http\Controllers\Api;


use App\Models\MovieRoom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MovieRoomResource;

class MovieRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $MovieRoom = MovieRoom::all();
        return MovieRoomResource::collection($MovieRoom);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $MovieRoom = MovieRoom::create($request->all());
        return new MovieRoomResource($MovieRoom);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $MovieRoom = MovieRoom::find($id);
        if(!$MovieRoom){
            return response()->json(['message'=>'MovieRoom not found'],404);
        }
        return new MovieRoomResource($MovieRoom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $MovieRoom = MovieRoom::find($id);
        if(!$MovieRoom){
            return response()->json(['message'=>'MovieRoom not found'],404);
        }
        $MovieRoom->update($request->all());
        return new MovieRoomResource($MovieRoom);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $MovieRoom = MovieRoom::find($id);
        if(!$MovieRoom){
            return response()->json(['message'=>'MovieRoom not found'],404);
        }
        $MovieRoom->delete();
        return response()->json(null,204);
    }
}
