<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\Profile\BaseController;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Requests\API\Auth\SocialAuthRequest;
use App\Http\Resources\API\Profile\ProfileResource;
use App\Models\User;
use App\Traits\FilesUploadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class  AuthController extends BaseController
{
    use FilesUploadTrait;

    /**
     * Validate the user login request.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = User::query()->where($request['type'], $request['username'])->first();

        if ($user && Hash::check($request['password'], $user['password'])) {

            return $this->respondData([
                'user' => new ProfileResource($user),
                'token' => generateToken($user)
            ]);
        }

        throw ValidationException::withMessages(['password' => 'Wrong password']);
    }


    /**
     * Validate the user login request.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('profile_picture')) {
            $validatedData['profile_picture'] = $this->uploadFile($request->profile_picture, 'users', 'user_' . getNextAutoIncrementId('users') . '.png');
        }

        $user = User::query()->create($validatedData);

        return $this->respondData([
            'user' => new ProfileResource($user),
            'token' => generateToken($user)
        ]);
    }


    /**
     * Validate the user login request.
     *
     * @param SocialAuthRequest $request
     * @return JsonResponse
     * TODO: Best Way For Social auth To separate the providers so user have many providers To Handle Possible Bugs and for Full Social Authentication Module
     */
    public function social(SocialAuthRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->profile_picture_url) {
            $validatedData['profile_picture'] = $this->uploadImageFromURL($request->profile_picture_url, 'users', 'user_' . getNextAutoIncrementId('users') . '.png');
        }

        $user = User::query()->where(['provider' => $request->provider ,'provider_id' => $request->provider])
            ->orWhere('email', $request->email)->first();

        if ($user){
            $user->update($validatedData);
        }else{
            $user = User::create($validatedData);
        }

        return $this->respondData([
            'user' => new ProfileResource($user),
            'token' => generateToken($user)
        ]);
    }

    /**
     * Revoke Token     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logOut(Request $request)
    {
        auth()->user()->tokens()->delete();

        return $this->respondMessage('logout successful');
    }

}
