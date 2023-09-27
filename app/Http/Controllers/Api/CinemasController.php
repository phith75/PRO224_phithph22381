<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CinemasResource;
use App\Models\Cinemas;
use Illuminate\Http\Request;

class CinemasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Cinemas::all();
        return CinemasResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Cinemas::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cinemas = Cinemas::find($id);
        if (!$cinemas) {
            return response()->json(['message' => "student not found"], 404);
        }
        return new CinemasResource($cinemas);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        Cinemas::where('id', $id)
            ->update($request->except('_token'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        Cinemas::where('id', $id)
            ->delete();
    }
}
