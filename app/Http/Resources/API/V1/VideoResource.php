<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'likes_count' => $this->likes()->count(),
            'comments_count' => $this->comments()->count(),
            'saves_count' => $this->saves()->count(),
            'description' => $this->description,
            'media' => MediaResource::collection($this->media),
            'user' => UserResource::make($this->user),
            'comments' => CommentResource::collection($this->comments),
            'is_liked' => auth()->check() ? $this->likes()->where('user_id', auth()->id())->exists() : false,
            'is_saved' => auth()->check() ? $this->saves()->where('user_id', auth()->id())->exists() : false,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
