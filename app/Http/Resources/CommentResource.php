<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // CommentResource.php
// CommentResource.php
public function toArray($request)
{
    return [
        'id' => $this->id,
        'blogs_id' => $this->blog_id,
        'user_name' => $this->user_name,
        'content' => $this->content,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'user' => new UserResource($this->whenLoaded('user')),
    ];
}
}
