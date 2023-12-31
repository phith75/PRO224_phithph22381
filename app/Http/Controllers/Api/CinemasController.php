<?php

namespace App\Http\Controllers\Api;

use App\Models\Cinemas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CinemasResource;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:cinemas,name',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $Cinemas = Cinemas::create($request->all());
        return new CinemasResource($Cinemas);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $cinemas = Cinemas::find($id);
        
        if (!$cinemas) {
            return response()->json(['message' => "Cinemas not found"], 404);
        }
        return new CinemasResource($cinemas);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Cinemas = Cinemas::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:cinemas,name,'.$id,
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if (!$Cinemas) {
            return response()->json(['message' => 'Cinemas not found'], 404);
        }
        Cinemas::where('id', $id)
            ->update($request->except('_token'));

        return new CinemasResource($Cinemas);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Cinemas = Cinemas::find($id);
        if (!$Cinemas) {
            return response()->json(['message' => 'Cinemas not found'], 404);
        }
        $Cinemas->delete();
        return response()->json(['message' => 'delete success'], 200);
    }
}
