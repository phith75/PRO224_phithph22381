<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // BlogResource.php
// BlogsResource.php
public function toArray($request)
{
    return [
        'id' => $this->id,
        'title' => $this->title,
        'slug' => $this->slug,
        'image' => $this->image,
        'content' => $this->content,
        'status' => $this->status,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
    ];
}


}
