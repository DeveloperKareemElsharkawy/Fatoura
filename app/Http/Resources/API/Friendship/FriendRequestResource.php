<?php

namespace App\Http\Resources\API\Friendship;

use App\Http\Resources\API\Profile\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendRequestResource extends JsonResource
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
            'status' => $this->status,
            'is_sender' => $this->from_user_id == auth()->user()->id,
            'from_user' => new ProfileResource($this->fromUser),
            'toUser' => new ProfileResource($this->toUser),
            'created_at' => $this->created_at ? $this->created_at->diffForHumans() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->diffForHumans() : null,
        ];
    }
}
