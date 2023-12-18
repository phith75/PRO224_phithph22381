<?php

namespace App\Http\Controllers\Api;

use App\Models\TimeDetail;
use Illuminate\Support\Facades\DB;

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
    public function get_time_detail_by_id_cinema($id_cinema)
    {   
        $TimeDetail = TimeDetail::join('movie_rooms as mv', 'mv.id', '=', 'time_details.room_id')
        ->join('cinemas as cms', 'cms.id', '=', 'mv.id_cinema')
        ->where('cms.id', $id_cinema)
        ->get();

return TimeDetailResource::collection($TimeDetail);

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $check_time_detail = DB::table('time_details')
            ->where('time_details.room_id', $request->room_id)           
            ->join('times as tms', 'tms.id', '=', 'time_details.time_id')
            ->where('time_details.time_id', $request->time_id)
            ->where('time_details.date', $request->date)
            ->whereNull('time_details.deleted_at')->get()->first();
            $ngay_format = date("d/m/Y", strtotime($request->date));
        if($check_time_detail){
            return response([
                'message' => 'Suất chiếu '. $ngay_format .' '.$check_time_detail->time .' đã tồn tại',
            ], 401);

        }
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
