<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Photos;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PhotoResource;


class PhotoController extends Controller
{
    public function index()
    {
        $fims = photos::all();
        return PhotoResource::collection($fims);
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
        $photos = photos::create($data);

        return new PhotoResource($photos);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $photos = photos::find($id);

        if (!$photos) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm photos.'], 404);
        }
        return new PhotoResource($photos);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $photos = photos::find($id);

        if (!$photos) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy phim.'], 404);
        }
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $resultDelete = Storage::delete('/' . $photos->image);
            if ($resultDelete) {
                $params['image'] = uploadFile('image', $request->file('image'));
            } else {
                $params['image'] = $photos->image;
            }
        }
        $photos->update($data);
        return response()->json(['message' => 'Phim đã được cập nhật thành công', 'photos' => $photos], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fims = photos::find($id);
        if (!$fims) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy photos.'], 404);
        }
        if ($fims->image && Storage::exists($fims->image)) {
            Storage::delete('public/' . $fims->image);
        }
        $fims->delete();
        return response()->json(['message' => 'photos đã được xóa']);
    }
}
