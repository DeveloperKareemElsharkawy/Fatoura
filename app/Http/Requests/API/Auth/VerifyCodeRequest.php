<?php

namespace App\Http\Requests\API\Auth;

use App\Rules\API\Auth\ResetPasswordCodeRule;
use Illuminate\Foundation\Http\FormRequest;

class VerifyCodeRequest extends FormRequest
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
            'username' => 'required|exists:password_resets,' . $this->username(),
            'reset_code' => ['required', new ResetPasswordCodeRule($this->username(),$this->input("username"))],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return string
     */
    public function username(): string
    {
        return (filter_var(request()['username'], FILTER_VALIDATE_EMAIL)) ? 'email' : 'mobile';
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'type' => $this->username(),
        ]);
    }
}
