<?php

namespace App\Http\Resources\API\Profile;

use App\Http\Resources\API\Location\CountryResource;
use App\Http\Resources\API\Location\StateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'profile_picture' => $this->profile_picture,

            'country' => new CountryResource($this->country),
            'state' => new StateResource($this->state),

            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'bio' => $this->bio,

            'created_at' => $this->created_at ? $this->created_at->diffForHumans() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->diffForHumans() : null,
        ];
    }
}
