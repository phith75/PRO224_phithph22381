<?php

namespace App\Http\Controllers\Api;

use App\Models\Film;
use App\Models\RateStar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RateStarResource;
use App\Models\Book_ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RateStarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $RateStar = RateStar::all();
        return RateStarResource::collection($RateStar);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $user = auth()->user();
    // Bắt validate request
    $check = RateStar::where(['user_id' => $user->id, 'film_id' => $request->film_id])->get()->first();
    
    $validator = Validator::make($request->all(), [
        'star_rating' => 'required|integer|min:1|max:5',
        'film_id' => 'required|exists:films,id',
    ]);
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }
    $film_bought = DB::table('users')
    ->join('book_tickets as bt', 'users.id', '=', 'bt.user_id')
    ->join('time_details', 'bt.id_time_detail', '=', 'time_details.id')
    ->join('films', 'time_details.film_id', '=', 'films.id')
    ->where('films.id', $request->film_id)
    ->where('bt.status', 1)
    ->select('films.id', 'films.name as film_name')
    ->first();

    if($film_bought){
        if(!empty($check)){
            return response(['message' => "Mỗi khách hàng chỉ được đánh giá 1 lần"]);
        }
        $rating = RateStar::create([
            'user_id' => $user->id,//lấy khi login
            'film_id' => $request->film_id, // 
            'star' => $request->star_rating,//đánh giá sao
            'comment' => $request->comment, //comment
        ]);
        return response()->json(['message' => 'Bình luận và đánh giá đã được thêm mới.', 'data' => $rating]);
    }
   
    return response()->json(['message' => 'Chỉ khách hàng đã xem phim này mới được đánh giá'],301);
    
    // Tạo mới bình luận và đánh giá
    
}

    //số lượng đánh giá sao
    public function getRatings($film_id)
    {   
        $user = auth()->user();

        // Lấy tất cả đánh giá cho bộ phim có film_id tương ứng
        $ratings = RateStar::where('film_id', $film_id)->get();

        // Tính trung bình số sao
        $averageStars = $ratings->avg('star');

        // Lấy số sao và comment mà user đang đăng nhập đã đánh giá (nếu có)
        $userRating = null;
        if (auth()->check()) {
            $userId = auth()->user()->id;
            $userRating = $ratings->where('user_id', $userId)->first();
        }

        $response = [
            'totalReviews' => $ratings->count(),
            'averageStars' => $averageStars,
        ];

        // Thêm thông tin đánh giá của user vào response
        if ($userRating) {
            $response['userRating'] = [
                'star' => $userRating->star,
                'comment' => $userRating->comment,
            ];
        }

        // Thêm tất cả đánh giá vào response
        $response['allRatings'] = $ratings;

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $RateStar = RateStar::find($id);
        if (!$RateStar) {
            return response()->json(['message' => 'RateStar not found'], 404);
        }
        return new RateStarResource($RateStar);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $RateStar = RateStar::find($id);
        if (!$RateStar) {
            return response()->json(['message' => 'RateStar not found'], 404);
        }
        $RateStar->update($request->all());
        return new RateStarResource($RateStar);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $RateStar = RateStar::find($id);
        if (!$RateStar) {
            return response()->json(['message' => 'RateStar not found'], 404);
        }
        $RateStar->delete();
        return response()->json(['message' => "delete success"], 200);
    }
}
