<?php

namespace App\Http\Requests\API\Auth;

use App\Rules\ResetPasswordCode;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|exists:password_resets,email',
            'reset_code' => ['required', new ResetPasswordCode($this->input('email'),$this->input('reset_code'))],
            'password' => 'required|confirmed|min:8',

        ];
    }

}
