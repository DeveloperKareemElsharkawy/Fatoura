<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\Profile\BaseController;
use App\Http\Requests\API\Auth\ResetPasswordRequest;
use App\Http\Resources\API\Profile\ProfileResource;
use App\Models\User;

class ResetPasswordController extends BaseController
{
    public function setNewPassword(ResetPasswordRequest $request)
    {
         $user = User::query()->where('email', $request['email'])->first();

        if (!$user) {
            return $this->respondError("user not found");
        }

        $user->update(['password' => $request['password']]);

        \DB::table('password_resets')->where('email', $user->email)->delete();

        return $this->respondData([
            'user' => new ProfileResource($user),
            'token' =>  $user->createToken(config('app.name'))->plainTextToken
        ], 'password has been reset successful');
    }
}
