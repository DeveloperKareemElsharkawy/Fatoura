<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\Profile\BaseController;
use App\Http\Requests\API\Auth\SendResetCodeRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ForgetPasswordController extends BaseController
{
    public function sendResetCode(SendResetCodeRequest $request)
    {
        try {
            User::query()->where($request['type'], $request['username'])->first();

            $reset_code = generateResetCode(6);

            DB::table('password_resets')
                ->updateOrInsert(
                    ['email' => $request['email']],
                    ['code' => $reset_code, 'created_at' => now()]
                );

//        $user->notify(new SendForgetPasswordCode($reset_code));

            return $this->respondMessage('Reset Password Code has been sent successfully');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return $this->respondError('Check SMTP Settings');
        }
    }


}
