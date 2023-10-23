<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Book_ticketResource;
use App\Models\Book_ticket;
use Illuminate\Http\Request;

class Book_ticketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Book_ticket::all();
        return Book_ticketResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Book_ticket = Book_ticket::create($request->all());
        return new Book_ticketResource($Book_ticket);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Book_ticket = Book_ticket::find($id);
        if (!$Book_ticket) {
            return response()->json(['message' => "student not found"], 404);
        }
        return new Book_ticketResource($Book_ticket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $Book_ticket = Book_ticket::find($id);
        if (!$Book_ticket) {
            return response()->json(['message' => 'Book ticket not found'], 404);
        }
        $Book_ticket::where('id', $id)
            ->update($request->except('_token'));

        return new Book_ticketResource($Book_ticket);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Book_ticket = Book_ticket::find($id);
        if (!$Book_ticket) {
            return response()->json(['message' => 'Book ticket not found'], 404);
        }
        $Book_ticket->delete();
        return response()->json(null, 204);
    }
}
