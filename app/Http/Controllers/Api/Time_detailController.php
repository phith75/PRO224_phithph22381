<?php

namespace App\Http\Controllers\Api;

use App\Models\TimeDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\TimeDetailResource;
use Illuminate\Support\Facades\Validator;

class Time_detailController extends Controller
{
    public function index()
    {
        $TimeDetail = TimeDetail::all();
        return TimeDetailResource::collection($TimeDetail);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Định nghĩa các quy tắc validation
        $rules = [
            'date' => [
                'required',
                'date',
                'after_or_equal:' . now()->format('Y-m-d'),
            ],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $timeDetail = TimeDetail::create($request->all());

        return new TimeDetailResource($timeDetail);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $TimeDetail = TimeDetail::find($id);
        if (!$TimeDetail) {
            return response()->json(['message' => 'MovieRoom not found'], 404);
        }
        return new TimeDetailResource($TimeDetail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Tìm TimeDetail theo id
        $timeDetail = TimeDetail::find($id);
        if (!$timeDetail) {
            return response()->json(['message' => 'TimeDetail not found'], 404);
        }
        $rules = [];
        if ($request->date) {
            $rules = [
                'date' => [
                    'required',
                    'date',
                    'after_or_equal:' . now()->format('Y-m-d'),
                ],
            ];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $timeDetail->update($request->all());

        return new TimeDetailResource($timeDetail);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $TimeDetail = TimeDetail::find($id);
        if (!$TimeDetail) {
            return response()->json(['message' => 'TimeDetail not found'], 404);
        }
        $TimeDetail->delete();
        return response()->json(['message' => "delete success"], 200);
    }
}
