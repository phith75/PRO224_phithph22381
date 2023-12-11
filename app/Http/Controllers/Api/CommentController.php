<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::all();
        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $blogId)
{
    // Lấy thông tin người dùng đã đăng nhập
    $user = auth()->user();

    // Kiểm tra xem người dùng đã đăng nhập hay không
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    // dd($blogId);
    // Tạo mới comment với các giá trị cần thiết
    $comment = Comment::create([
        'blogs_id' => $blogId, 
        'user_name' => $user->name,
        'content' => $request->input('content'),
    ]);
    return new CommentResource($comment);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => "Comment not found"], 404);
        }

        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $validatedData = $request->validate([
            'blog_id' => 'exists:blogs,id',
            'user_name' => 'string|max:255',
            'content' => 'string',
        ]);

        $comment->update($validatedData);

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => "Comment not found"], 404);
        }

        $comment->delete();

        return response()->json(['message' => "Comment deleted successfully"], 200);
    }
}
