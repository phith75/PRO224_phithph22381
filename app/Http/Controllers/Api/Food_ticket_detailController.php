<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Food_ticket_detail as ResourcesBook_ticket_detail;
use App\Http\Resources\Food_ticket_detailResource;
use App\Models\Food_ticket_detail;
use Illuminate\Http\Request;

class Food_ticket_detailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Food_ticket_detail::all();
        return Food_ticket_detailResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Book_ticket_detail = Food_ticket_detail::create($request->all());
        return new Food_ticket_detailResource($Book_ticket_detail);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Book_ticket_detail = Food_ticket_detail::find($id);
        if (!$Book_ticket_detail) {
            return response()->json(['message' => "Book ticket detail not found"], 404);
        }
        return new Food_ticket_detailResource($Book_ticket_detail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $Book_ticket_detail = Food_ticket_detail::find($id);
        if (!$Book_ticket_detail) {
            return response()->json(['message' => 'Book ticket detail not found'], 404);
        }
        Food_ticket_detail::where('id', $id)
            ->update($request->except('_token'));

        return new Food_ticket_detailResource($Book_ticket_detail);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $Book_ticket_detail = Food_ticket_detail::find($id);
        if (!$Book_ticket_detail) {
            return response()->json(['message' => 'Book ticket detail not found'], 404);
        }
        $Book_ticket_detail->delete();
        return response()->json(['message' => "delete success"], 200);
    }
}
