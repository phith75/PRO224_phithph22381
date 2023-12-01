<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\member;
use App\Http\Requests\StorememberRequest;
use App\Http\Requests\UpdatememberRequest;
use App\Http\Resources\MemberResource;
use App\Http\Resources\Book_ticketResource;
use App\Models\Book_ticket;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $data = member::all();
        return MemberResource::collection($data);
    }
    public function show(string $id)
    {
        $data = member::where('id_user', $id)->first();

        if (!$data) {
            return response()->json(['message' => 'member not found'], 404);
        }
        return new MemberResource($data);
    }

    public function update(Request $request, string $id)
    {
        $data = member::where('id_user', $id)->first();
        if (!$data) {
            return response()->json(['message' => 'member not found'], 404);
        }
        $usable_points =   $data->usable_points;
        $points_used =   $data->points_used;
        if ($data->usable_points >= $request->discount) {
            $usable_points -= $request->discount;
            $points_used += $request->discount;
        } else {
            return response()->json(['message' => 'Không còn đủ điểm']);
        }
        $data->update(['points_used' => $points_used, 'usable_points' => $usable_points]);
        return new MemberResource($data);
    }
}