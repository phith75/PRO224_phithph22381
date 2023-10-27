<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeedbackResource;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Feedback::all();
        return FeedbackResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Feedback::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Feedback = Feedback::find($id);
        if (!$Feedback) {
            return response()->json(['message' => "Feedback not found"], 404);
        }
        return new FeedbackResource($Feedback);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $Feedback = Feedback::find($id);
        if (!$Feedback) {
            return response()->json(['message' => 'Feedback not found'], 404);
        }
        Feedback::where('id', $id)
            ->update($request->except('_token'));

        return new FeedbackResource($Feedback);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $Feedback = Feedback::find($id);
        if (!$Feedback) {
            return response()->json(['message' => 'Feedback not found'], 404);
        }
        $Feedback->delete();
        return response()->json(null, 204);
    }
}
