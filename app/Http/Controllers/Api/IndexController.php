<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $tên bảng = tên bảng::all();
        // return tênbảngResource::collection($tên bảng); // trả ra 1 dạng list collection
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $tên bảng = tên bảng::create($request->all());
        // return new tênbảngResource($tên bảng); // trả ra 1
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $tên bảng = tên bảng::find($id);
        // if (!$tên biến) {
        //     return response()->json(['message'=>"data not found"],404);
        // }
        // return new StudentResource($tên biến);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $tên biến = tên bảng ::where('id', $id)->update($request->all());

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //tên bảng ::where('id', $id)->delete();
    }
}
