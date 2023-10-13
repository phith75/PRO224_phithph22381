<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Book_ticket_detail as ResourcesBook_ticket_detail;
use App\Http\Resources\Book_ticket_detailResource;
use App\Models\Book_ticket_detail;
use Illuminate\Http\Request;

class Book_ticket_detailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Book_ticket_detail::all();
        return Book_ticket_detailResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Book_ticket_detail::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Book_ticket_detail = Book_ticket_detail::find($id);
        if (!$Book_ticket_detail) {
            return response()->json(['message' => "student not found"], 404);
        }
        return new Book_ticket_detailResource($Book_ticket_detail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        Book_ticket_detail::where('id', $id)
            ->update($request->except('_token'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        Book_ticket_detail::where('id', $id)
            ->delete();
    }
}