<?php

namespace App\Http\Controllers\Api;

use Illuminate\Validation\Rule;
use App\Models\vocher;
use App\Http\Controllers\Controller;
use App\Http\Resources\VocherResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vocher = vocher::all();
        return VocherResource::collection($vocher);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                Rule::unique('vochers'),
            ],
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'usage_limit' => 'required|integer|min:0',
            'price_vocher' => 'required|integer|min:0',
            'limit' => 'required|integer|in:1,2',
            'minimum_amount' => 'required|integer|min:0',
            'percent' => 'required|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $voucher = Vocher::create($data);
        return new VocherResource($voucher);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vocher = vocher::find($id);
        if (!$vocher) {
            return response()->json(['message' => 'time not found'], 404);
        }
        return new VocherResource($vocher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $voucher = Vocher::find($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $data = $request->validate([
            'code' => [
                'required',
                Rule::unique('vochers')->ignore($voucher->id),
            ],
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'usage_limit' => 'required|integer|min:0',
            'price_vocher' => 'required|integer|min:0',
            'limit' => 'required|integer|in:1,2',

        ]);

        $voucher->update($data);

        return new VocherResource($voucher);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vocher = vocher::find($id);
        if (!$vocher) {
            return response()->json(['message' => 'vocher not found'], 404);
        }
        $vocher->delete();
        return response()->json(['message' => "delete success"], 200);
    }
}