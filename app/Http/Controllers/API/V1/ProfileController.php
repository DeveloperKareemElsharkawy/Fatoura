<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\Profile\BaseController;
use App\Http\Requests\API\User\UpdatePasswordRequest;
use App\Http\Requests\API\User\UpdateProfileRequest;
use App\Http\Resources\API\Profile\ProfileResource;
use App\Traits\FilesUploadTrait;
use Illuminate\Http\JsonResponse;

class ProfileController extends BaseController
{
    use FilesUploadTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @return JsonResponse
     */
    public function getProfile()
    {
        return $this->respondData(new ProfileResource(auth('api')->user()));
    }

    /**
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
         $user = auth('api')->user();

        $validatedData = $request->validated();

        if ($request->hasFile('profile_picture')) {
            $validatedData['profile_picture'] = $this->uploadFile($request->profile_picture, 'users', 'user_' . getNextAutoIncrementId('users') . '.png');
        }

        $user->update($validatedData);

        return $this->respondData([
            'user' => new ProfileResource($user),
            'token' => generateToken($user)
        ]);

    }

    /**
     * @param UpdatePasswordRequest $request
     * @return JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = auth('api')->user();

        $user->update(['password' => $request['password']]);

        return $this->respondMessage('Password updated successfully');
    }

}
