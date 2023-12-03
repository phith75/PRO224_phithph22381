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
        $data = $request->all();
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = uploadFile('image', $request->file('image'));
        }
        $films = film::create($data);

        return new FilmResource($films);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $films = Film::find($id);

        if (!$films) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm film.'], 404);
        }
        return new FilmResource($films);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $films = Film::find($id);

        if (!$films) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy phim.'], 404);
        }
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $resultDelete = Storage::delete('/' . $films->image);
            if ($resultDelete) {
                $params['image'] = uploadFile('image', $request->file('image'));
            } else {
                $params['image'] = $films->image;
            }
        }
        $films->update($data);
        return response()->json(['message' => 'Phim đã được cập nhật thành công', 'film' => $films], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fims = film::find($id);
        if (!$fims) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy film.'], 404);
        }
        if ($fims->image && Storage::exists($fims->image)) {
            Storage::delete('public/' . $fims->image);
        }
        $fims->delete();
        return response()->json(['message' => 'Film đã được xóa']);
    }
}