<?php

namespace App\Http\Resources\API\Profile;

use App\Http\Resources\API\Location\CountryResource;
use App\Http\Resources\API\Location\StateResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Friendship\GetMutualFriends;

class MutualFriendsResource extends JsonResource
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
            'name' => $this->name,
            'profile_picture' => $this->profile_picture,
        ];
    }

}
