<?php

namespace App\Http\Controllers\Api;

use Illuminate\Validation\Rule;
use App\Models\voucher;
use App\Http\Controllers\Controller;
use App\Http\Resources\voucherResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $voucher = voucher::all();
        return voucherResource::collection($voucher);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                Rule::unique('vouchers'),
            ],
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'usage_limit' => 'required|integer|min:0',
            'price_voucher' => 'required|integer|min:0',
            'limit' => 'required|integer|in:1,2',
            'minimum_amount' => 'required|integer|min:0',
            'percent' => 'required|integer|min:0|max:100',
            'description'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $voucher = voucher::create($data);
        return new voucherResource($voucher);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $voucher = voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'time not found'], 404);
        }
        return new voucherResource($voucher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $voucher = voucher::find($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $data = $request->validate([
            'code' => [
                'required',
                Rule::unique('vouchers')->ignore($voucher->id),
            ],
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'usage_limit' => 'required|integer|min:0',
            'price_voucher' => 'required|integer|min:0',
            'limit' => 'required|integer|in:1,2',
            'description'=>'required'

        ]);

        $voucher->update($data);

        return new voucherResource($voucher);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voucher = voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'voucher not found'], 404);
        }
        $voucher->delete();
        return response()->json(['message' => "delete success"], 200);
    }
}
