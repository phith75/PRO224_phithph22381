<?php

namespace App\Http\Controllers\Api;

use App\Models\Film;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FilmResource;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fims = Film::all();
        return FilmResource::collection($fims);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = uploadFile('image', $request->file('image'));
        }

        $fims = film::create($data);

        return new FilmResource($fims);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fims = Film::find($id);
        if(!$fims){
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm phim.'], 404);
        }
        return new FilmResource($fims);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validated();
        $fims = film::find($id);

        if (!$fims) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy phim.'], 404);
        }

        if($request->hasFile('image') && $request->file('image')->isValid()){
              
            $resultDelete = Storage::delete('/'.$fims->image);
            if($resultDelete){
                $params['image'] = uploadFile('image', $request->file('image'));
            }
            else{
                $params['image'] = $fims->image;
            }
        }

        $fims->update($data);

        return new FilmResource($fims);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fims = film::find($id);

        if (!$fims) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy phim.'], 404);
        }

        if ($fims->image && Storage::exists($fims->image)) {
            Storage::delete('public/'.$fims->image);
        }

        $fims->delete();

        return response()->json(['message' => 'Sản phẩm đã được xóa']);
    }
}
