<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogsResource;
use App\Models\Blogs;
use Illuminate\Http\Request;

class BlogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Blogs::all();
        return BlogsResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Blog = Blogs::create($request->all());
        return new BlogsResource($Blog);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Blogs = Blogs::find($id);
        if (!$Blogs) {
            return response()->json(['message' => "Blog not found"], 404);
        }
        return new BlogsResource($Blogs);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Blogs = Blogs::find($id);
        if (!$Blogs) {
            return response()->json(['message' => 'Blog not found'], 404);
        }
        Blogs::where('id', $id)
            ->update($request->except('_token'));

        return new BlogsResource($Blogs);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $Blog = Blogs::find($id);
        if (!$Blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }
        $Blog->delete();
        return response()->json(['message' => "delete success"], 200);
    }
}
