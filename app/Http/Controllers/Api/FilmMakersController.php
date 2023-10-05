<?php

namespace App\Http\Controllers\Api;

use App\Models\FilmMaker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FilmMakerResource;

class FilmMakersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $film_makers = FilmMaker::all();
        return FilmMakerResource::collection($film_makers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $film_makers = FilmMaker::create($request->all());
        return new FilmMakerResource($film_makers);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $film_makers = FilmMaker::find($id);
        if(!$film_makers){
            return response()->json(['message'=>'film_makers not found'],404);
        }
        return new FilmMakerResource($film_makers);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $film_makers = FilmMaker::find($id);
        if(!$film_makers){
            return response()->json(['message'=>'film_makers not found'],404);
        }
        $film_makers->update($request->all());
        return new FilmMakerResource($film_makers);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $film_makers = FilmMaker::find($id);
        if(!$film_makers){
            return response()->json(['message'=>'film_makers not found'],404);
        }
        $film_makers->delete();
        return response()->json(null,204);
    }
}
