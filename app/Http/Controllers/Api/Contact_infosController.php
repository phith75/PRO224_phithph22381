<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contact_infosResource;
use App\Models\Contact_infos;
use Illuminate\Http\Request;

class Contact_infosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Contact_infos::all();
        return Contact_infosResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Contact_infos = Contact_infos::create($request->all());
        return new Contact_infosResource($Contact_infos);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Contact_infos = Contact_infos::find($id);
        if (!$Contact_infos) {
            return response()->json(['message' => "Contact info not found"], 404);
        }
        return new Contact_infosResource($Contact_infos);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Contact_infos = Contact_infos::find($id);
        if (!$Contact_infos) {
            return response()->json(['message' => 'Contact info not found'], 404);
        }
        Contact_infos::where('id', $id)
            ->update($request->except('_token'));

        return new Contact_infosResource($Contact_infos);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Contact_infos = Contact_infos::find($id);
        if (!$Contact_infos) {
            return response()->json(['message' => 'Contact info not found'], 404);
        }
        $Contact_infos->delete();
        return response()->json(null, 204);
    }
}
