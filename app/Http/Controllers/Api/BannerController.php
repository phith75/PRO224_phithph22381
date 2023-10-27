<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Banner::all();
        return BannerResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Banner = Banner::create($request->all());
        return new BannerResource($Banner);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Banner = Banner::find($id);
        if (!$Banner) {
            return response()->json(['message' => "Banner not found"], 404);
        }
        return new BannerResource($Banner);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Banner = Banner::find($id);
        if (!$Banner) {
            return response()->json(['message' => 'Banner not found'], 404);
        }

        $Banner->update($request->all());

        return new BannerResource($Banner);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Banner = Banner::find($id);
        if (!$Banner) {
            return response()->json(['message' => 'Banner not found'], 404);
        }
        $Banner->delete();
        return response()->json(null, 204);
    }
}
