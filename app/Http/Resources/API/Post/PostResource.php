<?php

namespace App\Http\Resources\API\Post;

use App\Http\Resources\API\Profile\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'content' => $this->content,

            'is_owner' => $this->user_id == auth()->user()->id,
            'owner' => new ProfileResource($this->user),

            'image' => $this->image,
            'video' => $this->video,

            'shares_count' => $this->shares()->count(),
            'likes_count' => $this->likes()->count(),
            'comments_count' => $this->comments()->count(),

            'is_shared' => sharedByCurrentUser($this), // Check if the post is shared

            'is_liked' => $this->isLikedByCurrentUser(), // Check if the post is liked by the current user

            'created_at' => $this->created_at ? $this->created_at->diffForHumans() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->diffForHumans() : null,
        ];
    }
}
